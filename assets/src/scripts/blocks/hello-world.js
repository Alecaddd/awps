const { registerBlockType, RichText, BlockControls, AlignmentToolbar, source } = wp.blocks;

registerBlockType( 'gutenberg-test/hello-world', {
	title: 'Hello World',
	icon: 'universal-access-alt',
	category: 'layout',

	attributes: {
		content: {
			type: 'array',
			source: 'children',
			selector: 'p'
		},
		alignment: {
			type: 'string'
		}
	},

	edit( { attributes, className, isSelected, setAttributes } ) {
		const { content, alignment } = attributes;

		function onChangeContent( newContent ) {
			setAttributes( { content: newContent } );
		}

		function onChangeAlignment( newAlignment ) {
			setAttributes( { alignment: newAlignment } );
		}

		return [
			isSelected && (
				<BlockControls key="controls">
					<AlignmentToolbar
						value={ alignment }
						onChange={ onChangeAlignment }
					/>
				</BlockControls>
			),
			<RichText
				key="editable"
				tagName="p"
				className={ className }
				style={ { textAlign: alignment } }
				onChange={ onChangeContent }
				value={ content }
			/>
		];
	},

	save( { attributes, className } ) {
		const { content, alignment } = attributes;

		return <p className={ className } style={ { textAlign: alignment } }>{ content }</p>;
	}
} );
