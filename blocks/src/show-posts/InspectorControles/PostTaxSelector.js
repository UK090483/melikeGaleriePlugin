const { CheckboxControl } = wp.components;
const { PanelBody } = wp.components;
const { withState } = wp.compose;

let PostTaxSelector = withState({ tax: [] })(function(props) {
	const taxonomies = props.postTypes[props.attributes.selectedCategory]
		? props.postTypes[props.attributes.selectedCategory].taxonomies
		: null;

	function showTax() {
		if (taxonomies !== null && Object.keys(taxonomies).length > 0) {
			return printTax();
		} else {
			return <p>no Taxonomies</p>;
		}
	}

	function printTax() {
		return Object.keys(taxonomies).map(tax => {
			return (
				<div>
					<h4>{tax}</h4>
					{getTerms(tax)}
				</div>
			);
		});
	}

	function arrayRemove(arr, value) {
		return arr.filter(function(ele) {
			return ele != value;
		});
	}

	function getTerms(tax) {
		let terms = taxonomies[tax].terms;

		if (terms.length > 0) {
			!(terms[0].name === "noTerm") && terms.unshift({ name: "noTerm" });

			return Object.keys(terms).map(key => {
				let name = terms[key].name;
				let dbName = tax + "---" + name;

				function isChecked() {
					if (props.attributes.taxonomies.includes(dbName)) {
						return false;
					} else {
						return true;
					}
				}

				function handlechange(e) {
					let res = [];
					if (e) {
						res = arrayRemove(props.attributes.taxonomies, dbName);
					} else {
						res = [...props.attributes.taxonomies, dbName];
					}

					props.setAttributes({
						taxonomies: res
					});
				}

				return (
					<CheckboxControl
						label={name}
						checked={isChecked()}
						onChange={handlechange}
					/>
				);
			});
		} else {
			return;
			<h5>{"no Terms"}</h5>;
		}
	}

	return <div> {showTax()}</div>;
});

export default PostTaxSelector;
