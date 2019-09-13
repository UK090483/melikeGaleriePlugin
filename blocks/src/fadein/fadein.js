const { registerBlockType } = wp.blocks;
const { TextControl, DateTimePicker, BaseControl, TabPanel } = wp.components;

import "./style.scss";
import "./editor.scss";

registerBlockType("cgb/fade-block", {
	title: "Fade Block",
	icon: "smiley",
	category: "common",

	attributes: {
		text: {
			type: "string"
		}
	},

	edit({ className, setAttributes, attributes }) {
		function getText(text) {
			let res = attributes.text.split("").map(element => {
				return <span>{element}</span>;
			});
			console.log(res);
			return res;
		}

		return (
			<div>
				<TextControl
					onChange={text => {
						setAttributes({
							text
						});
					}}
					value={attributes.text}
				></TextControl>

				<div className="fade-animate">{attributes.text && getText()}</div>
			</div>
		);
	},

	// No information saved to the block
	// Data is saved to post meta via attributes
	save({ className, setAttributes, attributes }) {
		function getText(text) {
			let res = attributes.text.split("").map(element => {
				return <span>{element}</span>;
			});
			return res;
		}

		return <div className="fade-animate">{attributes.text && getText()}</div>;
	}
});
