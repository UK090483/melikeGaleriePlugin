const { registerBlockType } = wp.blocks;
const { TextControl,  Icon,PanelBody } = wp.components;
const { RichText, AlignmentToolbar, InspectorControls } = wp.editor;

import "./style.scss";
import "./editor.scss";
import facebook from './icons/facebook.js';
import twitter from './icons/twitter.js';





registerBlockType("cgb/sozial-links", {
	title: "Sozial",
	icon: "smiley",
	category: "common",

	attributes: {
		icons:{
			type:'object',
			default:{}
	
		},	
		ranunm:{
			type: 'number'
		}
		
	
	},

	edit({ setAttributes, attributes }) {

		const icons ={
			facebook :facebook,
			twitter:twitter
		}
		

		function getIcons(){
			if(attributes.icons){
			return Object.keys(icons).map((key)=>{
				return (
				<div>
					<h1>{key}</h1>
					<Icon icon={icons[key]}></Icon>
					<TextControl
						label= {key}
						value={attributes.icons.hasOwnProperty(key)?attributes.icons[key]:'dfklö' }
						onChange={ ( value ) => handleChange(key, value) }
					></TextControl>
				</div>
				)
			})}
		}


		function handleChange(key,value){
			let atr = attributes.icons;
			atr[key]=value;
			setAttributes({
				icons:atr,
			 ranunm: Math.random()
			})
		}
		


		return (
			<div>

				<InspectorControls>
					<PanelBody title={"Icons"} initialOpen={false}>
						{getIcons()}
					</PanelBody>
				</InspectorControls>
				<Icon icon={facebook}></Icon>
				<Icon icon={twitter}></Icon>
			</div>
		);
	},

	
	save({ className, setAttributes, attributes }) {
		

		return <div>
		<Icon icon={facebook}></Icon>
		<Icon icon={twitter}></Icon>
		</div>
	}
});
