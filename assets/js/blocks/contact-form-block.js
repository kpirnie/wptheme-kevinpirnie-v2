/** Contact form editor placeholder. */
(function(blocks,element,components){
 const el=element.createElement;
 blocks.registerBlockType('kpt/contact-form-block',{
  title:'Contact Form',icon:'email',category:'kpt-blocks',
  edit(){return el(components.Placeholder,{icon:'email',label:'Contact Form'},
   el('p',null,'Contact form appears on the frontend.'));},
  save(){return null;}
 });
})(wp.blocks,wp.element,wp.components);
