const {chromium}=require('C:/Users/lucia/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/playwright');
const fs=require('fs');
const assert=require('node:assert/strict');
(async()=>{
const browser=await chromium.launch({executablePath:'C:/Users/lucia/.cache/puppeteer/chrome-headless-shell/win64-147.0.7727.56/chrome-headless-shell-win64/chrome-headless-shell.exe'});
const results=[];
for(const width of [360,768,1280]){
 const page=await browser.newPage({viewport:{width,height:900}});
 for(const path of ['/','/centro-de-dia/','/noticias/','/noticias-aisa/novo-servico-terapia-ocupacional/','/agenda/','/agenda/festa-natal/']){
 const errors=[];page.on('pageerror',e=>errors.push(e.message));
 await page.addInitScript(() => { window.demoLcp = null; new PerformanceObserver(list => { window.demoLcp = list.getEntries().at(-1)?.startTime; }).observe({type:'largest-contentful-paint',buffered:true}); });
 const response=await page.goto('http://aisa.wordpress.test'+path,{waitUntil:'networkidle'});
 await page.evaluate(async()=>{for(const img of document.images){img.loading='eager';} await Promise.all([...document.images].map(i=>i.decode().catch(()=>{})));await document.fonts.ready;});
 const data=await page.evaluate(()=>({lcp:window.demoLcp,overflow:document.documentElement.scrollWidth>innerWidth,h1:[...document.querySelectorAll('h1')].map(e=>e.textContent),headings:[...document.querySelectorAll('main h1,main h2,main h3')].map(e=>e.tagName+':'+e.textContent),broken:[...document.images].filter(i=>!i.complete||!i.naturalWidth).map(i=>i.src),news:document.querySelectorAll('article.news-card').length,lead:document.querySelectorAll('.news-card--lead').length,fonts:[...document.fonts].map(f=>({family:f.family,status:f.status})),nav:[...document.querySelectorAll('.site-nav,.site-nav .wp-block-navigation__container')].map(e=>({c:e.className,display:getComputedStyle(e).display,direction:getComputedStyle(e).flexDirection}))}));
 const slug=path==='/'?'inicio':path.split('/').filter(Boolean).pop();
 await page.screenshot({path:`public_html/wp-content/themes/aisa/tools/${slug}-${width}.png`,fullPage:true});
 assert.equal(response.status(),200,path); assert.equal(data.h1.length,1,path); assert.equal(data.overflow,false,path); assert.equal(data.broken.length,0,path); assert.equal(errors.length,0,path);
 let previous=1;for(const heading of data.headings){const level=Number(heading[1]);assert(level<=previous+1,'Hierarquia '+path+heading);previous=level;}
 if(path==='/'||path==='/noticias/'){assert.equal(data.lead,1);assert.equal(data.news,4);}
 results.push({width,path,status:response.status(),...data,errors});
 }
 await page.close();
}
fs.writeFileSync('public_html/wp-content/themes/aisa/tools/demo-browser-results.json',JSON.stringify(results,null,2));
console.log(results.map(r=>({width:r.width,path:r.path,status:r.status,overflow:r.overflow,h1:r.h1.length,broken:r.broken.length,news:r.news,lead:r.lead,errors:r.errors})));
console.log(results[0].nav);console.log(results[2].headings);
await browser.close();
})().catch(e=>{console.error(e);process.exit(1)});
