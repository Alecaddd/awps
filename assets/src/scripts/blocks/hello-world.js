const { registerBlockType } = wp.blocks;
const { Fragment } = wp.element;
const {
	RichText,
	BlockControls,
	AlignmentToolbar
} = wp.editor;

registerBlockType( 'gutenberg-test/hello-world', {
	title: 'Hello World',
	icon: 'universal-access-alt',
	category: 'layout',

	attributes: {
		content: {
			type: 'string',
			source: 'html',
			selector: 'p'
		},
		alignment: {
			type: 'string'
		}
	},

	edit({ attributes, className, setAttributes }) {
		const { content, alignment } = attributes;

		function onChangeContent( newContent ) {
			setAttributes({ content: newContent });
		}

		function onChangeAlignment( newAlignment ) {
			setAttributes({ alignment: newAlignment });
		}

		return (
			<Fragment>
				<BlockControls>
					<AlignmentToolbar
						value={alignment}
						onChange={onChangeAlignment}
					/>
				</BlockControls>
				<RichText
					key="editable"
					tagName="p"
					className={className}
					style={{ textAlign: alignment }}
					onChange={onChangeContent}
					value={content}
				/>
			</Fragment>
		);
	},

	save({ attributes }) {
		const { content, alignment } = attributes;

		return (
			<RichText.Content
				style={{ textAlign: alignment }}
				value={content}
				tagName="p"
			/>
		);
	}
});
