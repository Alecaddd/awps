const { registerBlockType } = wp.blocks;

registerBlockType( 'gutenberg-test/hello-world', {
	title: 'Hello World',
	icon: 'universal-access-alt',
	category: 'layout',

	edit( { className } ) {
		return <p className={ className }>Hello editor.</p>;
	},

	save( { className } ) {
		return <p className={ className }>Hello saved content.</p>;
	}
} );
