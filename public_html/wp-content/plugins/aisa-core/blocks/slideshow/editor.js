(function (wp) {
 'use strict';
 var el = wp.element.createElement, __ = wp.i18n.__, C = wp.components;
 var slug = "slideshow";
 var attributes = {"slides": {"type": "array", "default": [], "items": {"type": "object"}}, "label": {"type": "string", "default": "Fotografias da AISA"}};
 wp.blocks.registerBlockType('aisa/' + slug, {
  apiVersion: 3, title: __("Slideshow", 'aisa'), category: 'aisa', icon: 'heart', attributes: attributes,
  edit: function (props) {
   var a = props.attributes, controls = [], warnings = [];
   var valencias = wp.data.useSelect(function (select) { return select('core').getEntityRecords('taxonomy', 'aisa_valencia', { per_page: 100, hide_empty: false }); }, []);
   var tipos = wp.data.useSelect(function (select) { return select('core').getEntityRecords('taxonomy', 'aisa_tipo_documento', { per_page: 100, hide_empty: false }); }, []);
   function update(key, value) { var next = {}; next[key] = value; props.setAttributes(next); }
   function text(key, label, value, callback) { return el(C.TextControl, { key: key, label: __(label, 'aisa'), value: value === undefined ? a[key] : value, onChange: callback || function (v) { update(key, v); } }); }
   if ('valencia' in a) controls.push(el(C.SelectControl, { key: 'valencia', label: __('Valência', 'aisa'), value: a.valencia, options: [{ label: __('Todas', 'aisa'), value: '' }].concat((valencias || []).map(function (t) { return { label: t.name, value: t.slug }; })), onChange: function (v) { update('valencia', v); } }));
   if ('quantidade' in a) controls.push(el(C.RangeControl, { key: 'quantidade', label: __('Quantidade', 'aisa'), value: a.quantidade, min: 1, max: slug === 'depoimentos' ? 3 : 12, onChange: function (v) { update('quantidade', v); } }));
   if ('titulo' in a) controls.push(text('titulo', 'Título'));
   if ('ano' in a) { controls.push(text('ano', 'Ano dos dados (obrigatório)')); if (!a.ano.trim()) warnings.push(__('Indica o ano para mostrar as provas.', 'aisa')); }
   if ('label' in a) controls.push(text('label', 'Nome acessível'));
   if ('variante' in a) controls.push(el(C.SelectControl, { key: 'variante', label: __('Variante', 'aisa'), value: a.variante, options: [{label: __('Botão', 'aisa'),value:'botao'},{label: __('Grande', 'aisa'),value:'grande'},{label: __('Texto', 'aisa'),value:'texto'}], onChange: function(v){ update('variante',v); } }));
   if ('ordem' in a) controls.push(el(C.SelectControl, { key:'ordem', label:__('Ordem','aisa'), value:a.ordem, options:[{label:__('Mais recentes','aisa'),value:'recente'},{label:__('Aleatória','aisa'),value:'aleatoria'}], onChange:function(v){update('ordem',v);} }));
   if ('mostrarLigacao' in a) controls.push(el(C.ToggleControl,{key:'agenda',label:__('Mostrar ligação para a agenda','aisa'),checked:a.mostrarLigacao,onChange:function(v){update('mostrarLigacao',v);}}));
   if ('tipos' in a) (tipos || []).forEach(function(t){controls.push(el(C.CheckboxControl,{key:t.slug,label:t.name,checked:a.tipos.indexOf(t.slug)!==-1,onChange:function(checked){update('tipos',checked?a.tipos.concat(t.slug):a.tipos.filter(function(s){return s!==t.slug;}));}}));});
   if ('items' in a) {
    a.items.forEach(function(item,i){
     ['numero','legenda'].forEach(function(key){ controls.push(text(key+i,key==='numero'?'Número':'Legenda',item[key] || '',function(v){update('items',a.items.map(function(old,j){return i===j?Object.assign({},old,{[key]:v}):old;}));})); });
     controls.push(el(C.Button,{key:'remove'+i,variant:'secondary',onClick:function(){update('items',a.items.filter(function(_,j){return i!==j;}));}},__('Remover prova','aisa')));
    });
    controls.push(el(C.Button,{key:'add',variant:'secondary',onClick:function(){update('items',a.items.concat({numero:'',legenda:''}));}},__('Adicionar prova','aisa')));
   }
   if ('slides' in a) {
    controls.push(el(wp.blockEditor.MediaUploadCheck,{key:'media'},el(wp.blockEditor.MediaUpload,{allowedTypes:['image'],multiple:true,gallery:true,value:a.slides.map(function(s){return s.id;}),onSelect:function(media){update('slides',media.map(function(m){var old=a.slides.find(function(s){return s.id===m.id;});return {id:m.id,alt:old?old.alt:(m.alt || ''),caption:old?(old.caption || ''):(m.caption || '')};}));},render:function(p){return el(C.Button,{variant:'secondary',onClick:p.open},__('Selecionar fotografias','aisa'));}})));
    a.slides.forEach(function(slide,i){ controls.push(text('caption'+i,__('Legenda','aisa')+' '+(i+1),slide.caption || '',function(v){update('slides',a.slides.map(function(s,j){return i===j?Object.assign({},s,{caption:v}):s;}));})); controls.push(text('alt'+i,__('Texto alternativo (obrigatório)','aisa')+' '+(i+1),slide.alt || '',function(v){update('slides',a.slides.map(function(s,j){return i===j?Object.assign({},s,{alt:v}):s;}));})); if(!(slide.alt || '').trim()) warnings.push(__('Falta texto alternativo na fotografia','aisa')+' '+(i+1)); });
   }
   return el('div',wp.blockEditor.useBlockProps(),
    el(wp.blockEditor.InspectorControls,null,el(C.PanelBody,{title:__('Definições AISA','aisa')},controls)),
    warnings.map(function(w,i){return el(C.Notice,{key:i,status:'warning',isDismissible:false},w);}),
    el(wp.serverSideRender,{block:'aisa/'+slug,attributes:a})
   );
  },
  save: function () { return null; }
 });
}(window.wp));
