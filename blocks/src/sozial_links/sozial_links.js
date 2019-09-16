const { registerBlockType } = wp.blocks;
const { TextControl,  Icon,PanelBody,RangeControl } = wp.components;
const { RichText, AlignmentToolbar, InspectorControls } = wp.editor;

import "./style.scss";
import "./editor.scss";
import facebook from './icons/facebook.js';
import twitter from './icons/twitter.js';


const possibleIcons ={
	facebook :facebook,
	twitter:twitter
}


registerBlockType("cgb/sozial-links", {
	title: "Sozial",
	icon: "smiley",
	category: "common",

	attributes: {
		icons:{
			type:'object',
			default:{}
		},	
		
		size:{
			type:'number',
			default:24.0
		}
		
	
	},

	edit({ setAttributes, attributes,className }) {

		const {size, icons}= attributes;

		function isValidURL(str) {
			let a  = document.createElement('a');
			a.href = str;
			return (a.host && a.host != window.location.host);
		 }
		

		function getIcons(){
			
			return Object.keys(possibleIcons).map((key)=>{

				
				let url = icons.hasOwnProperty(key)?icons[key]:'' 
				let style = isValidURL(url)?{}:{backgroundColor:'rgba(255, 43, 43, 0.192)'};
				return (
				<div>
					<a style={{width: size+'px',height:size+'px'}}>
							{possibleIcons[key]()}
					</a>
				<div  style={style}>
					<TextControl
					
						label= {key}
						value={url}
						onChange={ ( value ) => handleChange(key, value) }
					></TextControl>
					</div>
				</div>
				)
			})
		}


		function handleChange(key,value){
			let atr = {...icons};
			atr[key]=value;
			setAttributes({
				icons:{...atr},
			})
		}

		function getRenderedIcons(){
			return Object.keys(possibleIcons).map((key)=>{
				if(icons.hasOwnProperty(key) && icons[key] !== '' ){
				return (
			
					<a  style={{width: size+'px',height:size+'px'}}>
							{possibleIcons[key]()}
					</a>
			
				)}
			})
		}
		


		return (
			<div className={className}>

				<InspectorControls>
				<RangeControl
					label="Size"
					value={ size }
					onChange={ ( size ) => setAttributes( { size } ) }
					min={ 20 }
					max={ 200 }
				/>
					<PanelBody title={"Icons"} initialOpen={false}>
						{getIcons()}
					</PanelBody>
				</InspectorControls>
					{getRenderedIcons()}
			</div>
		);
	},

	
	save({className,attributes}) {
		const {size, icons}= attributes;

		function getRenderedIcons(){
			return Object.keys(possibleIcons).map((key)=>{
				if(icons.hasOwnProperty(key) && icons[key] !== '' ){
				return (
			
					<a rel="noopener noreferrer"  target="_blank" href={icons[key]} style={{width: size+'px',height:size+'px'}}>
							{possibleIcons[key]()}
					</a>
			
				)}
			})
		}

		return <div className={className}>{getRenderedIcons()}</div>
	}
});
