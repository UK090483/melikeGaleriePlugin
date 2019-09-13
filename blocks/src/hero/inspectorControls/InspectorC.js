import PicMedia from "./PicMedia";
const { InspectorControls } = wp.editor;
const { ToggleControl, BaseControl, TextControl } = wp.components;

export default function InspectorC({ attributes, setAttributes }) {
	const { auto } = attributes;
	return (
		<InspectorControls>
			<BaseControl label="Auto/Manuell">
				<ToggleControl
					checked={auto}
					onChange={() => {
						setAttributes({ auto: !auto });
					}}
				></ToggleControl>
			</BaseControl>
			<TextControl
				label="Header"
				value={attributes.header}
				onChange={header => setAttributes({ header })}
			></TextControl>
			<TextControl
				label="Text"
				value={attributes.text}
				onChange={text => setAttributes({ text })}
			></TextControl>
			<TextControl
				label="Date"
				value={attributes.date}
				onChange={date => setAttributes({ date })}
			></TextControl>
			<PicMedia
				attributes={attributes}
				setAttributes={setAttributes}
			></PicMedia>
		</InspectorControls>
	);
}
