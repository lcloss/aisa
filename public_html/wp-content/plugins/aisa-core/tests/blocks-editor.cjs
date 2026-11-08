const fs = require('node:fs');
const vm = require('node:vm');
const assert = require('node:assert/strict');
const path = require('node:path');
const base = path.join(__dirname, '../blocks');
function node(dataset = {}) {
 return { dataset, hidden: false, attrs: {}, handlers: {}, textContent: '',
  addEventListener(type, fn) { this.handlers[type] = fn; },
  setAttribute(name, value) { this.attrs[name] = value; },
  hasAttribute(name) { return name in this.attrs; }
 };
}
function fixture(count) {
 const slides = Array.from({length: count}, () => node());
 const controls = node(), play = node({start:'Reproduzir', stop:'Pausar'});
 const status = node({format:'%1$d de %2$d'}), prev = node(), next = node(), root = node();
 const lookup = {'.slideshow__controls':controls,'[data-play]':play,'[data-status]':status,'[data-prev]':prev,'[data-next]':next};
 root.querySelector = s => lookup[s]; root.querySelectorAll = () => slides;
 return {slides,controls,play,status,prev,next,root};
}
const first = fixture(3), second = fixture(2), single = fixture(1);
const timers = new Map(); let timerId = 0;
const document = node(); const visibilityHandlers = []; document.addEventListener = (type, fn) => visibilityHandlers.push(fn); document.querySelectorAll = () => [first.root,second.root,single.root];
const sandbox = { document, setInterval(fn, ms) { assert.equal(ms,6000); timers.set(++timerId,fn); return timerId; }, clearInterval(id) { timers.delete(id); } };
vm.runInNewContext(fs.readFileSync(path.join(base,'slideshow/view.js'),'utf8'),sandbox);
assert.equal(timers.size,0,'no autoplay');
assert.deepEqual(first.slides.map(s=>s.hidden),[false,true,true]);
assert.equal(first.status.textContent,'1 de 3');
first.next.handlers.click(); assert.equal(first.status.textContent,'2 de 3');
assert.equal(second.status.textContent,'1 de 2','independent slideshows');
first.prev.handlers.click(); assert.equal(first.status.textContent,'1 de 3');
first.prev.handlers.click(); assert.equal(first.status.textContent,'3 de 3','wrap around');
first.play.handlers.click(); assert.equal(timers.size,1); assert.equal(first.play.textContent,'Pausar');
[...timers.values()][0](); assert.equal(first.status.textContent,'1 de 3');
first.play.handlers.click(); assert.equal(timers.size,0); assert.equal(first.play.attrs['aria-label'],'Reproduzir');
first.play.handlers.click(); first.root.handlers.focusin(); assert.equal(timers.size,0,'stop on focus');
first.play.handlers.click(); first.root.handlers.mouseenter(); assert.equal(timers.size,0,'stop on hover');
first.play.handlers.click(); document.hidden=true; visibilityHandlers.forEach(fn => fn()); assert.equal(timers.size,0,'stop in background');
assert.equal(single.controls.hidden,true);
assert.match(fs.readFileSync(path.join(base,'slideshow/style.css'),'utf8'),/prefers-reduced-motion: reduce/);
console.log('PASS slideshow: manual, previous/next, wrap, 6000 ms play/pause, focus, hover, background, independent instances, single slide, reduced-motion rule');
for(const slug of fs.readdirSync(base)) {
 const json=JSON.parse(fs.readFileSync(path.join(base,slug,'block.json'),'utf8'));
 let registered;
 const wp={blocks:{registerBlockType(name,settings){registered={name,settings};}},element:{createElement(type,props,...children){return {type,props,children};}},i18n:{__(s){return s;}},components:{},blockEditor:{useBlockProps(){return {}; }},data:{useSelect(fn){return fn(()=>({getEntityRecords(){return [];}}));}},serverSideRender:'ServerSideRender'};
 vm.runInNewContext(fs.readFileSync(path.join(base,slug,'editor.js'),'utf8'),{window:{wp}});
 assert.equal(registered.name,json.name);
 const attributes=Object.fromEntries(Object.entries(json.attributes).map(([k,v])=>[k,v.default]));
 const output=registered.settings.edit({attributes,setAttributes(){}});
 assert.ok(JSON.stringify(output).includes('ServerSideRender'));
 assert.equal(registered.settings.save(),null);
 console.log('PASS editor registration, native controls and server preview: '+slug);
}
