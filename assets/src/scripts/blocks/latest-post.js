const { registerBlockType } = wp.blocks;
const { withAPIData } = wp.components;

registerBlockType( 'gutenberg-test/latest-post', {
    title: 'Latest Post',
    icon: 'megaphone',
    category: 'widgets',

    edit: withAPIData( () => {
        return {
            posts: '/wp/v2/posts?per_page=1'
        };
    } )( ( { posts, className } ) => {
        var post;
        if ( ! posts.data ) {
            return 'loading !';
        }
        if ( 0 === posts.data.length ) {
            return 'No posts';
        }
        post = posts.data[ 0 ];

        return <a className={ className } href={ post.link }>
            { post.title.rendered }
        </a>;
    } ),

    save() {

        // Rendering in PHP
        return null;
    }
} );
