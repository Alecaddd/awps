const { registerBlockType } = wp.blocks;
const { withAPIData } = wp.components;

registerBlockType( 'gutenberg-test/latest-post', {
	title: 'Latest Post',
	icon: 'megaphone',
	category: 'widgets',
	
	edit: withSelect( ( select ) => {
		return {
		    posts: select( 'core' ).getEntityRecords( 'postType', 'post' )
        	};
	} )( ( { posts, className } ) => {
		if ( posts && posts.length === 0 ) {
		    return "No posts";
		}
		var post = posts[ 0 ];

		return <a className={ className } href={ post.link }>
			    { post.title.rendered }
			</a>;
	}),

    	save() {
		// Rendering in PHP
		return null;
    	}
});
