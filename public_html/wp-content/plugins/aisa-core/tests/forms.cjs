/* Local HTTP checks. Usage: node .../tests/forms.cjs <Armour field> <voluntariado ID> <contacto ID> */
const assert = require('node:assert/strict');
const base = process.env.AISA_TEST_URL || 'http://aisa.wordpress.test';
assert.match(base, /^http:\/\/[a-z0-9.-]+\.test$/);
const field = process.argv[2];
assert.match(field || '', /^[a-z]+\d+$/);
const ids = {voluntariado:Number(process.argv[3]), contacto:Number(process.argv[4])};
Object.values(ids).forEach(id => assert.ok(Number.isSafeInteger(id) && id > 0, 'Pass both provisioned form IDs'));
const token = 'XXXX.DUMMY.TOKEN.XXXX';
let checks = 0;
function check(value, label) { assert.ok(value, label); console.log('PASS ' + label); checks++; }
async function send(kind, changes = {}, omitted = []) {
  const id = ids[kind];
  const values = {
    _wpcf7_unit_tag: `wpcf7-f${id}-o1`, nome: 'Teste automático local AISA',
    telefone: '214857700', consentimento: '1', alt_s: '', [field]: '12345',
    _wpcf7_turnstile_response: token,
    ...(kind === 'voluntariado' ? {area:'Eventos', maior18:'Não', 'disponibilidade[]':'Fins de semana'} : {mensagem:'Teste local sem dados pessoais.'}),
    ...changes
  };
  omitted.forEach(key => delete values[key]);
  const body = new FormData();
  Object.entries(values).forEach(([key,value]) => body.append(key,value));
  const response = await fetch(`${base}/wp-json/contact-form-7/v1/contact-forms/${id}/feedback`, {method:'POST',body});
  assert.equal(response.status, 200);
  return response.json();
}
(async () => {
  for (const kind of Object.keys(ids)) {
    const all = ['nome','telefone','consentimento','area','maior18','disponibilidade[]','mensagem'];
    let empty = await send(kind, {}, all);
    check(empty.status === 'validation_failed' && empty.invalid_fields.some(f => f.field === 'nome'), kind + ': empty form rejected');
    empty = await send(kind, {}, all.filter(name => name !== 'nome'));
    check(empty.status === 'validation_failed' && empty.invalid_fields.some(f => f.field === 'consentimento') && empty.invalid_fields.some(f => f.field === 'telefone'), kind + ': name-only form rejected');
    let result = await send(kind, {}, ['telefone']);
    check(result.status === 'validation_failed' && ['telefone','email'].every(name => result.invalid_fields.some(f => f.field === name && f.message === 'Indique pelo menos um contacto' && f.error_id)), kind + ': no contact rejected with associated errors');
    result = await send(kind, {}, ['consentimento']);
    check(result.status === 'validation_failed' && result.invalid_fields.some(f => f.field === 'consentimento'), kind + ': unchecked consent rejected');
    result = await send(kind, {email:'invalid-address'});
    check(result.status === 'validation_failed' && result.invalid_fields.some(f => f.field === 'email'), kind + ': malformed email rejected even with telephone');
    result = await send(kind, {}, ['_wpcf7_turnstile_response']);
    check(result.status === 'spam', kind + ': missing Turnstile token rejected');
    result = await send(kind, {}, [field]);
    check(result.status === 'validation_failed', kind + ': missing WP Armour field rejected');
    result = await send(kind, {alt_s:'bot'});
    check(result.status === 'validation_failed', kind + ': filled honeypot rejected');
    result = await send(kind);
    check(result.status === 'mail_sent', kind + ': telephone-only SMTP delivery');
    result = await send(kind, {email:'teste@example.test'}, ['telefone']);
    check(result.status === 'mail_sent', kind + ': email-only SMTP delivery');
  }
  for (const field of ['area','maior18','disponibilidade[]']) {
    const result = await send('voluntariado', {}, [field]);
    check(result.status === 'validation_failed' && result.invalid_fields.some(f => f.field === field.replace('[]','')), 'voluntariado: required ' + field);
  }
  const result = await send('contacto', {}, ['mensagem']);
  check(result.status === 'validation_failed' && result.invalid_fields.some(f => f.field === 'mensagem'), 'contacto: required message');
  const messages = await (await fetch('http://127.0.0.1:8025/api/v1/messages')).json();
  for (const subject of ['Candidatura a voluntário', 'Contacto pelo site']) {
    const matching = messages.messages.filter(m => m.Subject.startsWith(subject));
    check(matching.some(m => m.ReplyTo.length === 0) && matching.some(m => m.ReplyTo.some(r => r.Address === 'teste@example.test')), subject + ': Mailpit received with conditional Reply-To');
    check(matching.every(m => m.From.Name === 'Site AISA' && m.To[0].Address === 'formularios@aisa.wordpress.test'), subject + ': correct sender and LOCAL recipient');
  }
  console.log(`${checks} integration checks passed.`);
})().catch(error => { console.error(error); process.exitCode = 1; });
