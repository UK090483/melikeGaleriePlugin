const { Component } = wp.element;

export default class PrintPosts extends Component {
	/**
	 * Constructor for PostSelector Component.
	 * Sets up state, and creates bindings for functions.
	 * @param object props - current component properties.
	 */
	constructor(props) {
		super(...arguments);
		this.props = props;

		this.state = {
			posts: [],
			loading: false,
			selectedCategory: ""
		};

		this.fetchPosts = this.fetchPosts.bind(this);
	}

	componentDidMount() {
		this.fetchPosts(this.props.selectedCategory);
	}
	componentDidUpdate(prevProps, prevState, snapshot) {
		if (prevProps.attributes !== this.props.attributes) {
			this.fetchPosts(this.props.selectedCategory);
		}
		if (prevProps.postTypes !== this.props.postTypes) {
			this.fetchPosts(this.props.selectedCategory);
		}
	}

	fetchPosts(e) {
		if (this.props.postTypes[e]) {
			let params = {
				posttype: this.props.postTypes[e].rest_base,
				taxonomies:
					this.props.attributes.taxonomies.length > 0
						? this.props.attributes.taxonomies
						: "",

				kudata: JSON.stringify({
					posttype: this.props.postTypes[e].name,
					taxonomies:
						this.props.attributes.taxonomies.length > 0
							? this.props.attributes.taxonomies
							: "",

					numOfPosts: this.props.attributes.numOfPosts
				})
			};

			var esc = encodeURIComponent;
			var query = Object.keys(params)
				.map(k => esc(k) + "=" + esc(params[k]))
				.join("&");

			wp.apiFetch({
				path: "/ku/v1/printposts?" + query
			}).then(posts => {
				this.setState({
					posts: posts
				});
			});
		}
	}

	render() {
		return (
			<div className="ku-card-wrap">
				{this.state.posts &&
					this.state.posts.map(post => {
						return (
							<a className="ku-card">
								{post.thumbnail && (
									<div
										className="ku-card-pic"
										style={{
											backgroundImage: `url(${post.thumbnail})`
										}}
									/>
								)}
								<h4 style={{ textAlign: "center" }}>{post.title.rendered}</h4>
							</a>
						);
					})}
			</div>
		);
	}
}
