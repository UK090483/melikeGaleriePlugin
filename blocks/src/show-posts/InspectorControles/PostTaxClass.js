const { Component } = wp.element;

export default class PostTaxClass extends Component {
	constructor(props) {
		super(...arguments);
		this.props = props;
		this.state = {};

		this.fetchTax = type => {
			wp.apiFetch({
				path: "/wp/v2/" + type
			}).then(pt => {
				this.setState({
					postTypes: pt
				});
				// console.log(pt);
			});
		};

		this.getTaxonomies = () => {
			let res = [];
			if (this.props.postTypes) {
				this.props.postTypes.forEach(element => {
					if (element.name === this.props.attributes.selectedCategory) {
						res = [...element.taxonomies];
					}
				});
			}
			return res;
		};
	}

	componentDidUpdate(prevProps, prevState) {
		this.getTaxonomies();
		// this.props.attributes.selectedCategory !==
		// 	prevProps.attributes.selectedCategory &&
		// 	// this.fetchTax(
		// 	// 	this.props.postTypes[this.props.attributes.selectedCategory].taxonomies
		// 	// );
	}

	componentDidMount() {
		this.getTaxonomies();

		// this.fetchTax(this.props.attributes.selectedCategory);
	}

	render() {
		return <div></div>;
	}
}
