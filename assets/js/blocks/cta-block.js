/** CTA editor block. */
(function (blocks, element, blockEditor, components, data, SSR) {
    const el = element.createElement;
    blocks.registerBlockType('kpt/cta-block', {
        title: 'CTA Display', icon: 'megaphone', category: 'kpt-blocks',
        attributes: { ctaId: { type: 'number', default: 0 } },
        edit(props) {
            const ctas = data.useSelect(select => select('core').getEntityRecords('postType','kpt_cta',
                { per_page: 100, status: 'publish', orderby: 'title', order: 'asc' }), []);
            const options = [{label:'Select a Call To Action',value:0}];
            if (Array.isArray(ctas)) ctas.forEach(cta => options.push({label:cta.title.rendered || `CTA #${cta.id}`,value:cta.id}));
            const selector = el(components.SelectControl, {label:'Call To Action',value:props.attributes.ctaId,
                options, onChange:value => props.setAttributes({ctaId:parseInt(value,10)||0})});
            return el(element.Fragment,null,
                el(blockEditor.InspectorControls,null,el(components.PanelBody,{title:'CTA Settings'},selector)),
                props.attributes.ctaId ? el(SSR,{block:'kpt/cta-block',attributes:props.attributes}) :
                el(components.Placeholder,{label:'Call To Action',icon:'megaphone'},selector));
        }, save() {return null;}
    });
})(wp.blocks,wp.element,wp.blockEditor,wp.components,wp.data,wp.serverSideRender);
