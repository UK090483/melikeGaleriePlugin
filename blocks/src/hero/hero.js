/**
 * BLOCK: my-block
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import "./style.scss";
import "./editor.scss";
import InspectorC from "./inspectorControls/InspectorC";

// import CoverEdit from "./edit.js";

const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks
const { withSelect } = wp.data;

const { MediaPlaceholder, InnerBlocks } = wp.editor;
const { ServerSideRender } = wp.components;

/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType("cgb/hero", {
	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
	title: __("HERO2"), // Block title.
	icon: "shield", // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
	category: "common", // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	keywords: [__(" — CGB Block"), __("CGB Example"), __("create-guten-block")],
	supports: {
		align: ["wide", "full"]
	},
	attributes: {
		imageId: {
			type: "number"
		},
		auto: {
			type: "boolean"
		},
		header: {
			type: "string"
		},
		text: {
			type: "string"
		},
		date: {
			type: "string"
		}
	},

	edit: withSelect(function(select, props) {
		let imageUrl = props.attributes.imageId
			? select("core").getMedia(props.attributes.imageId)
			: undefined;
		return {
			imageUrl: imageUrl
		};
	})(function(props) {
		return (
			<div>
				<ServerSideRender block="cgb/hero" attributes={props.attributes} />
				<InspectorC
					attributes={props.attributes}
					setAttributes={props.setAttributes}
				></InspectorC>
				{/* <InnerBlocks></InnerBlocks> */}
			</div>
		);
	}),

	save: function() {
		// return <InnerBlocks.Content />;
		return null;
	}
});
