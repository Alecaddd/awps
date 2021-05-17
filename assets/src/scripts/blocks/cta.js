const { registerBlockType } = wp.blocks;
const {
	PlainText,
	RichText,
	MediaUpload,
	BlockControls,
	InspectorControls,
	ColorPalette,
} = wp.editor;
const { IconButton, RangeControl, PanelBody } = wp.components;

registerBlockType( 'gutenberg-awps/awps-cta', {
	title: 'Call to Action',
	icon: 'format-image',
	category: 'layout',

	attributes: {
		title: {
			type: 'string',
			source: 'html',
			selector: 'h3',
		},
		body: {
			type: 'string',
			source: 'html',
			selector: 'p',
		},
		titleColor: {
			type: 'string',
			default: 'white',
		},
		bodyColor: {
			type: 'string',
			default: 'white',
		},
		overlayColor: {
			type: 'string',
			default: 'black',
		},
		overlayOpacity: {
			type: 'number',
			default: 0.3,
		},
		backgroundImage: {
			type: 'string',
			default: null,
		},
		url: {
			type: 'string',
			source: 'attribute',
			selector: 'a',
			attribute: 'href',
		},
		buttonText: {
			type: 'string',
			selector: 'a',
			default: 'Call to action',
		},
	},

	edit( { attributes, className, setAttributes } ) {
		const {
			title,
			body,
			backgroundImage,
			titleColor,
			bodyColor,
			overlayColor,
			overlayOpacity,
			url,
			buttonText,
		} = attributes;

		function onSelectImage( newImage ) {
			setAttributes( { backgroundImage: newImage.sizes.full.url } );
		}

		function onChangeBody( newBody ) {
			setAttributes( { body: newBody } );
		}

		function onChangeTitle( newTitle ) {
			setAttributes( { title: newTitle } );
		}

		function onTitleColorChange( newColor ) {
			setAttributes( { titleColor: newColor } );
		}

		function onBodyColorChange( newColor ) {
			setAttributes( { bodyColor: newColor } );
		}

		function onOverlayColorChange( newColor ) {
			setAttributes( { overlayColor: newColor } );
		}

		function onOverlayOpacityChange( newOpacity ) {
			setAttributes( { overlayOpacity: newOpacity } );
		}

		function changeButtonText( newText ) {
			setAttributes( { buttonText: newText } );
		}

		function onChangeUrl( newUrl ) {
			setAttributes( { url: newUrl } );
		}

		return (
			<div>
				<InspectorControls style={ { marginBottom: '40px' } }>
					<PanelBody title={ 'Font Color Settings' }>
						<div style={ { marginTop: '20px' } }>
							<p>
								<strong>Select a Title color:</strong>
							</p>
							<ColorPalette
								value={ titleColor }
								onChange={ onTitleColorChange }
							/>
						</div>
						<div
							style={ {
								marginTop: '20px',
								marginBottom: '40px',
							} }
						>
							<p>
								<strong>Select a Body color:</strong>
							</p>
							<ColorPalette
								value={ bodyColor }
								onChange={ onBodyColorChange }
							/>
						</div>
					</PanelBody>
					<PanelBody title={ 'Background Image Settings' }>
						<p>
							<strong>Select a background image:</strong>
						</p>
						<MediaUpload
							onSelect={ onSelectImage }
							type="image"
							value={ backgroundImage }
							render={ ( { open } ) => (
								<IconButton
									className="editor-media-placeholder__button is-button is-default is-large"
									icon="upload"
									onClick={ open }
								>
									Background Image
								</IconButton>
							) }
						/>
						<div
							style={ {
								marginTop: '20px',
								marginBottom: '40px',
							} }
						>
							<p>
								<strong>Overlay Color:</strong>
							</p>
							<ColorPalette
								value={ overlayColor }
								onChange={ onOverlayColorChange }
							/>
						</div>
						<RangeControl
							label={ 'Overlay Opacity' }
							value={ overlayOpacity }
							onChange={ onOverlayOpacityChange }
							min={ 0 }
							max={ 1 }
							step={ 0.05 }
						/>
					</PanelBody>
				</InspectorControls>
				<div
					className="cta-container"
					style={ {
						backgroundImage: `url(${ backgroundImage })`,
						backgroundSize: 'cover',
						backgroundPosition: 'center',
						backgroundRepeat: 'no-repeat',
					} }
				>
					<div
						className="cta-overlay"
						style={ {
							background: overlayColor,
							opacity: overlayOpacity,
						} }
					></div>
					<div className="cta-content">
						<RichText
							key="editable"
							tagName="h3"
							className={ className }
							placeholder="Your CTA title"
							onChange={ onChangeTitle }
							value={ title }
							style={ { color: titleColor } }
						/>
						<BlockControls></BlockControls>
						<RichText
							key="editable"
							tagName="p"
							className={ className }
							placeholder="Your CTA Description"
							onChange={ onChangeBody }
							value={ body }
							style={ { color: bodyColor } }
						/>
						<div className="cta-content-button">
							<RichText
								tagName="a"
								onChange={ changeButtonText }
								title={ buttonText }
								value={ buttonText }
								target="_blank"
							/>
						</div>
						<PlainText
							style={ {
								color: '#333',
								fontSize: '12px',
								padding: '2px',
							} }
							value={ url }
							onChange={ onChangeUrl }
							placeholder={ 'http://' }
						/>
					</div>
				</div>
			</div>
		);
	},

	save( { attributes } ) {
		const {
			title,
			body,
			titleColor,
			bodyColor,
			overlayColor,
			overlayOpacity,
			backgroundImage,
			url,
			buttonText,
		} = attributes;

		return (
			<div
				className="cta-container"
				style={ {
					backgroundImage: `url(${ backgroundImage })`,
					backgroundSize: 'cover',
					backgroundPosition: 'center',
					backgroundRepeat: 'no-repeat',
				} }
			>
				<div
					className="cta-overlay"
					style={ {
						background: overlayColor,
						opacity: overlayOpacity,
					} }
				></div>
				<div className="cta-content">
					<h3 style={ { color: titleColor } }>{ title }</h3>
					<RichText.Content
						tagName="p"
						value={ body }
						style={ { color: bodyColor } }
					/>
					<div className="cta-content-button">
						<RichText.Content
							tagName="a"
							href={ url }
							title={ buttonText }
							value={ buttonText }
							target="_blank"
						/>
					</div>
				</div>
			</div>
		);
	},
} );
