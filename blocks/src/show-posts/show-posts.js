/**
 * BLOCK: my-block
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import "./style.scss";
import "./editor.scss";
import PostinspectorControlls from "./InspectorControles/PostinspectorControlls.js";
import PrintPosts from "./printPosts/PrintPosts.js";

const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks

const { Component } = wp.element;
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
registerBlockType("cgb/show-posts", {
	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
	title: __("Show Posts"), // Block title.
	icon: "screenoptions", // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
	category: "common", // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	keywords: [__(" — CGB Block"), __("CGB Example"), __("create-guten-block")],
	attributes: {
		selectedCategory: {
			type: "string",
			default: ""
		},
		taxonomies: {
			type: "array"
		},
		numOfPosts: {
			type: "number",
			default: -1
		},

		dateQuery:{
			type:'object',
			default:{
				fromDateActive: false,
				fromDateNow: true,
				fromDate:  null,
				toDateActive: false,
				toDateNow:true,
				toDate: null,
			}
		}
	},

	edit: class Edit extends Component {
		constructor(props) {
			super(...arguments);
			this.props = props;
			this.state = {
				postTypes: []
			};
		}

		componentDidMount() {
			wp.apiFetch({
				path: "/ku/v1/showposts"
			}).then(pt => {
				this.setState({
					postTypes: pt
				});
				// console.log(pt);
			});
		}

		render() {
			return (
				<div>
					<PostinspectorControlls
						setAttributes={this.props.setAttributes}
						attributes={this.props.attributes}
						postTypes={this.state.postTypes}
					/>

					<ServerSideRender
						block="cgb/show-posts"
						attributes={{ isGutenberg: true, ...this.props.attributes }}
					/>

					{/* <PrintPosts
						selectedCategory={this.props.attributes.selectedCategory}
						postTypes={this.state.postTypes}
						attributes={this.props.attributes}
					/> */}
				</div>
			);
		}
	},

	save: function() {
		return null;
	}
});
