import PostTaxSelector from "./PostTaxSelector";
import PostTaxClass from "./PostTaxClass";

const { PanelBody, SelectControl } = wp.components;

const { withSelect } = wp.data;

var PostTypeSelector = withSelect(function(select) {
	return {
		spostTypes: select("core").getPostTypes()
	};
})(function(props) {
	
	function getOptions() {
		if (props.spostTypes) {
			let filter =['Pages','Media','Blocks'];
			let filteredPostTypes = props.spostTypes.filter((item)=>{
				return !filter.includes(item.name)

			})
			return filteredPostTypes.map(item => {
				return {
					label: item.name,
					value: item.slug
				};
			});
		}
	}
	function handleChange(e) {
		props.setAttributes({
			taxonomies: [],
			selectedCategory: e
		});
	}

	return (
		<PanelBody title={"Post Type"} initialOpen={false}>
			<SelectControl
				label="Select PostType"
				value={props.attributes.selectedCategory}
				options={getOptions()}
				onChange={handleChange}
			/>
		    {/* <PostTaxClass
				setAttributes={props.setAttributes}
				attributes={props.attributes}
				postTypes={props.spostTypes}
			></PostTaxClass>  */}
			<PostTaxSelector
				setAttributes={props.setAttributes}
				attributes={props.attributes}
				postTypes={props.postTypes}
			></PostTaxSelector>
		</PanelBody>
	);
});

export default PostTypeSelector;
