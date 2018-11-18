const { registerBlockType } = wp.blocks;
const { withAPIData, withSelect } = wp.data;

registerBlockType( 'gutenberg-test/latest-post', {
	title: 'Latest Post',
	icon: 'megaphone',
	category: 'widgets',

	edit: withSelect( ( select ) => {
		return {
			posts: select( 'core' ).getEntityRecords( 'postType', 'post' )
		};
	})( ({ posts, className }) => {
		var post = posts[ 0 ];

		if ( posts && 0 === posts.length ) {
			return 'No posts';
		}

		return <a className={ className } href={ post.link }>
			{ post.title.rendered }
		</a>;
	}),

	save() {

		// Rendering in PHP
		return null;
	}
});
