/* §6 + the complete page matrix. Run from the project root. No forms are submitted. */
const fs = require('node:fs');
const assert = require('node:assert/strict');
const {chromium} = require('C:/Users/lucia/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/playwright');
const axe = process.env.AISA_AXE_PATH || 'C:/var/www/Laravel/gerador/vendor/pestphp/pest-plugin-browser/resources/js/axe.min.js';
const root = 'public_html/wp-content/themes/aisa/tools/';
const base = 'http://aisa.wordpress.test';
let browser;
const section6 = process.argv.includes('--section6');
const slugs = ['servicos','servico-apoio-domiciliario','novas-instalacoes','atividades-de-animacao-e-de-apoio-a-familia','componente-de-apoio-a-familia','atividades-de-enriquecimento-curricular','saude-e-reabilitacao','candidaturas','protocolos','quem-somos','equipa-tecnica','orgaos-sociais','parcerias','documentos','torne-se-socio','apoie-a-nossa-causa','voluntariado','contactos','politica-de-privacidade','termos','declaracao-de-acessibilidade','centro-de-dia'];
(async () => {
 browser = await chromium.launch({executablePath:'C:/Users/lucia/.cache/puppeteer/chrome-headless-shell/win64-147.0.7727.56/chrome-headless-shell-win64/chrome-headless-shell.exe'});
 const results = [];
 const paths = section6 ? ['servicos','centro-de-dia','contactos','documentos','voluntariado'].map(s=>'/'+s+'/') : [...slugs.map(s => '/'+s+'/'), '/pagina-inexistente-02-04/', '/?s=zzsemresultados02', '/?s=apoio'];
 for (const width of (section6 ? [360] : [360,1280])) {
  const page = await browser.newPage({viewport:{width,height:900},reducedMotion:'reduce'});
  for (const path of paths) {
   const requests = [];
   const listener = r => requests.push(r.url());
   page.on('request',listener);
   const response = await page.goto(base+path,{waitUntil:'load'});
   await page.evaluate(async () => { await document.fonts.ready; });
   await page.addScriptTag({path:axe});
   const audit = await page.evaluate(async (allRules) => {
    const a = await axe.run(document, allRules ? {} : {runOnly:{type:'tag', values:['wcag2a','wcag2aa','wcag21a','wcag21aa','wcag22aa']}});
    return {version:axe.version,violations:a.violations.map(v => ({id:v.id,impact:v.impact,nodes:v.nodes.map(n=>({target:n.target,summary:n.failureSummary}))}))};
   }, section6);
   const data = await page.evaluate(() => ({
    overflow:document.documentElement.scrollWidth > innerWidth,
    h1:document.querySelectorAll('h1').length,
    broken:[...document.querySelectorAll('main img')].filter(i=>i.complete&&!i.naturalWidth).map(i=>i.src),
    headings:[...document.querySelectorAll('main h1,main h2,main h3,main h4')].map(e=>({level:Number(e.tagName[1]),text:e.textContent.trim()})),
    html:document.querySelector('main').innerHTML,
   }));
   const failures = [];
   if (response.status() !== (path.includes('pagina-inexistente')?404:200)) failures.push('HTTP '+response.status());
   if (data.overflow) failures.push('overflow');
   if (data.h1!==1) failures.push('h1 '+data.h1);
   if (data.broken.length) failures.push('broken images');
   if (audit.violations.some(v=>['serious','critical'].includes(v.impact))) failures.push('axe serious/critical');
   let previous = 1;
   for (const heading of data.headings) { if(heading.level>previous+1) failures.push('heading jump: '+heading.text); previous=heading.level; }
   if (path==='/contactos/') {
    if (requests.some(u=>u.includes('maps.googleapis.com')) || data.html.includes('<iframe')) failures.push('embedded map');
    if (!(await page.locator('main img[src$="mapa-aisa.png"]').count())) failures.push('static map missing');
    assert.equal(await page.locator('main .form-contacto').count(),1);
   }
   if (path==='/candidaturas/') assert.equal(await page.locator('.aisa-service-hours').count(),0);
   if (path==='/voluntariado/') assert.equal(await page.locator('main .form-voluntariado').count(),1);
   if (path==='/saude-e-reabilitacao/') {
    for (const anchor of await page.locator('.aisa-anchor-index a').evaluateAll(a=>a.map(e=>e.getAttribute('href')))) assert.equal(await page.locator(anchor).count(),1,anchor);
   }
   if (path==='/equipa-tecnica/') {
    assert.equal(await page.locator('.aisa-person img').count(),0);
    assert.equal(await page.locator('.aisa-person h3').count(),4);
    assert(!/Carla Sequeira|Marisa Nunes|Ana Beatriz Gomes|Susana Vilarinho/.test(data.html));
   }
   if (path==='/servico-apoio-domiciliario/') assert(!/€|euros/i.test(data.html));
   if (width===360 && ['/servicos/','/contactos/','/equipa-tecnica/'].includes(path)) await page.screenshot({path:root+'remaining-'+path.split('/')[1]+'-'+width+'.png',fullPage:true});
   if(width===1280 && path==='/servicos/') await page.screenshot({path:root+'remaining-servicos-1280.png',fullPage:true});
   results.push({width,path,status:response.status(),...audit,overflow:data.overflow,h1:data.h1,broken:data.broken,failures});
   console.log(width,path,failures.length?'FAIL '+failures.join(', '):'PASS');
   page.removeListener('request',listener);
  }
  await page.close();
 }
 const page = await browser.newPage();
 await page.goto(base+'/',{waitUntil:'load'});
 const links = await page.locator('header a,footer a').evaluateAll(a=>[...new Set(a.map(e=>e.href))]);
 const linkResults = [];
 for (const url of links.filter(u=>u.startsWith(base))) {
  const response = await page.request.get(url);
  linkResults.push({url,status:response.status()});
 }
 const emptyLinks = await page.locator('header a[href="#"],footer a[href="#"]').count();
 await page.goto(base+'/wp-content/themes/aisa/tools/validate-native.php',{waitUntil:'load'});
 const native = await page.evaluate(()=>window.validation);
 const report = {date:new Date().toISOString(),results,links:linkResults,emptyLinks,native};
 fs.writeFileSync(root+(section6?'remaining-section6.json':'remaining-verification.json'),JSON.stringify(report,null,2));
 await browser.close();
 assert.equal(results.flatMap(r=>r.failures).length,0,JSON.stringify(results.filter(r=>r.failures.length)));
 assert(linkResults.every(r=>r.status===200),JSON.stringify(linkResults));
 assert.equal(emptyLinks,0);
 assert.equal(native.length,0,JSON.stringify(native));
 console.log('PASS full matrix, menu/footer links, native blocks');
})().catch(async e=>{console.error(e);if(browser) await browser.close();process.exitCode=1;});
