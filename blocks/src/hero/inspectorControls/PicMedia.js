const { PanelBody, Button } = wp.components;
const { MediaUpload } = wp.editor;
const { __ } = wp.i18n;

export default function PicMedia({ attributes, setAttributes }) {
	const { imageId } = attributes;

	function onSelectImage(media) {
		setAttributes({
			imageId: media.id
		});
	}
	return (
		<PanelBody title={"Image"} initialOpen={false}>
			<MediaUpload
				title={__("Select Image")}
				onSelect={onSelectImage}
				allowedTypes={["image"]}
				value={imageId}
				render={({ open }) => (
					<Button isDefault onClick={open}>
						{!imageId ? __("Select Image") : __("Replace image")}
					</Button>
				)}
			/>
		</PanelBody>
	);
}
