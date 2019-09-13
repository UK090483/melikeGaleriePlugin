import PostTypeSelector from "./PostTypeSelector.js";
import PostQuantitieSelector from "./PostQuantitieSelector.js";
import PostDateControls from "./PostDateControls.js";

const { RichText, AlignmentToolbar, InspectorControls } = wp.editor;
const { PanelBody, BaseControl } = wp.components;

export default function PostinspectorControlls(props) {
	return (
		<InspectorControls>
			<PostTypeSelector
				setAttributes={props.setAttributes}
				attributes={props.attributes}
				postTypes={props.postTypes}
			/>

			<PostQuantitieSelector
				setAttributes={props.setAttributes}
				attributes={props.attributes}
			/>

			<PostDateControls
				setAttributes={props.setAttributes}
				attributes={props.attributes}
			></PostDateControls>
		</InspectorControls>
	);
}
