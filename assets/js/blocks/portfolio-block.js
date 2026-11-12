/** Portfolio grid editor block. */
(function(blocks,element,blockEditor,components,SSR){
 const el=element.createElement;
 blocks.registerBlockType('kpt/portfolio-block',{
  title:'Portfolio',icon:'images-alt2',category:'kpt-blocks',
  attributes:{imageSize:{type:'string',default:'portfolio'}},
  edit(props){const control=el(components.SelectControl,{label:'Image Size',value:props.attributes.imageSize,
   options:[{label:'Portfolio Featured',value:'kp-portfolio'},{label:'Portfolio Card',value:'kp-portfolio-card'}],
   onChange:value=>props.setAttributes({imageSize:value})});
   return el(element.Fragment,null,el(blockEditor.InspectorControls,null,
    el(components.PanelBody,{title:'Portfolio Settings'},control)),
    el(SSR,{block:'kpt/portfolio-block',attributes:props.attributes}));},
  save(){return null;}
 });
})(wp.blocks,wp.element,wp.blockEditor,wp.components,wp.serverSideRender);
