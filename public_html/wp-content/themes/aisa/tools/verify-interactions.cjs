const assert=require('node:assert/strict');
const fs=require('fs');
const {chromium}=require('C:/Users/lucia/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/playwright');
(async()=>{
const b=await chromium.launch({executablePath:'C:/Users/lucia/.cache/puppeteer/chrome-headless-shell/win64-147.0.7727.56/chrome-headless-shell-win64/chrome-headless-shell.exe'});
const page=await b.newPage({viewport:{width:360,height:900},reducedMotion:'reduce'});
await page.goto('http://aisa.wordpress.test/',{waitUntil:'networkidle'});
assert.equal(await page.locator('[data-aisa-slideshow] [aria-hidden="false"]').count(),1);
const initial=await page.locator('[data-status]').textContent();
await page.waitForTimeout(6500);
assert.equal(await page.locator('[data-status]').textContent(),initial,'Sem avanço automático');
await page.locator('[data-next]').click();
assert.notEqual(await page.locator('[data-status]').textContent(),initial,'Navegação manual');
await page.locator('.site-header__menu').click();
assert.equal(await page.locator('#aisa-menu-panel').getAttribute('aria-modal'),'true');
for(let i=0;i<15;i++){await page.keyboard.press('Tab');assert(await page.evaluate(()=>document.querySelector('#aisa-menu-panel').contains(document.activeElement)),'Foco dentro do menu');}
await page.keyboard.press('Escape');
assert.equal(await page.locator('.site-header__menu').getAttribute('aria-expanded'),'false');
assert(await page.locator('.site-header__menu').evaluate(e=>e===document.activeElement));
await page.goto('http://aisa.wordpress.test/noticias/',{waitUntil:'networkidle'});
for(const article of await page.locator('article.news-card').all()){assert.equal(await article.locator('a').count(),1,'Uma ligação por notícia');assert.equal(await article.locator('img').getAttribute('alt'),'');assert(await article.locator('time').getAttribute('datetime'));}
await page.goto('http://aisa.wordpress.test/centro-de-dia/',{waitUntil:'networkidle'});
assert.equal(await page.locator('.page-header').getAttribute('data-valencia'),'centro-dia');
assert(await page.locator('.aisa-service-info').textContent().then(t=>t.includes('8h30')));
await page.goto('http://aisa.wordpress.test/agenda/',{waitUntil:'networkidle'});
assert.equal(await page.locator('main article.event').count(),3);
const times=await page.locator('main article.event time').evaluateAll(els=>els.map(e=>e.dateTime));
assert.deepEqual(times,[...times].sort());
await page.goto('http://aisa.wordpress.test/agenda/festa-natal/',{waitUntil:'networkidle'});
assert.equal(await page.locator('main time').count(),2);
assert(await page.locator('main').textContent().then(t=>t.includes('local a confirmar')));
await b.close();
fs.writeFileSync('public_html/wp-content/themes/aisa/tools/demo-interactions.json',JSON.stringify({passed:true,checks:['slideshow manual, reduced-motion','menu modal, ciclo de Tab, Escape e foco','uma ligação, alt vazio e time por notícia','Centro de Dia: cor e horário','agenda: 3 eventos por ordem','evento: início, fim e local']},null,2));
console.log('PASS interações de teclado, slideshow, cartões e eventos');
})().catch(e=>{console.error(e);process.exit(1)});
