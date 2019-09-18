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
				// this.setState({
				// 	postTypes: pt
				// });
				 console.log(pt);
			});
		};

		this.getTaxonomies = () => {
			if (this.props.postTypes && this.props.attributes.selectedCategory) {
				let postElement = this.getPostElement()
				let res = postElement? postElement.taxonomies : false;
				console.log(res);
				if(res){
					this.getclaims(res);
				}
			}
			
		};

		this.getPostElement=()=>{
			return this.props.postTypes.find((element)=>{
				return this.props.attributes.selectedCategory === element.slug })
		}

		this.getclaims=(tax)=>{
			tax.forEach(element => {
				this.fetchTax(element);
			});
		}
	}

	componentDidUpdate(prevProps, prevState) {
		console.log(this.getTaxonomies())
	
	}

	componentDidMount() {
		console.log(this.getTaxonomies())

	}

	render() {
		return <div></div>;
	}
}
