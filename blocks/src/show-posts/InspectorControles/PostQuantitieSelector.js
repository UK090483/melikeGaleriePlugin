const { BaseControl, CheckboxControl, TextControl, PanelBody } = wp.components;

export default function PostQuantitieSelector({ attributes, setAttributes }) {
	const { numOfPosts } = attributes;
	function handelChange(num) {
		setAttributes({
			numOfPosts: num
		});
	}
	return (
		<PanelBody title={"Post Quatity"} initialOpen={false}>
			<BaseControl label={"how many posts?"}>
				<CheckboxControl
					label="All"
					checked={numOfPosts === -1}
					onChange={num => {
						num ? handelChange(-1) : handelChange(3);
					}}
				/>

				{numOfPosts !== -1 && (
					<TextControl
						label="Additional CSS Class"
						value={numOfPosts}
						type={"number"}
						onChange={num => {
							handelChange(num);
						}}
					/>
				)}
			</BaseControl>
		</PanelBody>
	);
}
