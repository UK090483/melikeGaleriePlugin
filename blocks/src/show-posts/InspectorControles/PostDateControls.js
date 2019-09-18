const { PanelBody, DateTimePicker, BaseControl, FormToggle } = wp.components;

export default function PostDateControls(props) {
	const { fromDateActive,fromDateNow, fromDate,toDateActive,toDateNow,toDate  } = props.attributes.dateQuery;
	

	

	function handleToggleChange(attr) {
		let dq= {...props.attributes.dateQuery}
		dq[attr] = !dq[attr]
		props.setAttributes({
			dateQuery: dq
		});
	}

	function handleDatechange(attr,date){
		console.log(date);
		let dq= {...props.attributes.dateQuery}
		dq[attr] = date
		props.setAttributes({
			dateQuery: dq
		});
	}


	

	return (
		<PanelBody title={"Date"} initialOpen={false}>
			<BaseControl label="From">
				<FormToggle
					checked={fromDateActive}
					onChange={()=>handleToggleChange('fromDateActive')}
				></FormToggle>
			</BaseControl>

			{fromDateActive && (
				<BaseControl label="now">
					<FormToggle
						checked={fromDateNow}
						onChange={()=>handleToggleChange('fromDateNow')}
					></FormToggle>
				</BaseControl>
			)}
 
			{fromDateActive && !fromDateNow && (
				<DateTimePicker
					onChange={(date)=>handleDatechange('fromDate',date)}
					currentDate={fromDate}
				></DateTimePicker>
			)} 

			<hr style={{ borderTop: "5px solid" }}></hr>

			<BaseControl label="To">
				<FormToggle
					checked={toDateActive}
					onChange={()=>handleToggleChange('toDateActive')}
				></FormToggle>
			</BaseControl>

			{toDateActive && (
				<BaseControl label="now">
					<FormToggle
						checked={toDateNow}
						onChange={()=>handleToggleChange('toDateNow')}
					></FormToggle>
				</BaseControl>
			)}
 
			{toDateActive && !toDateNow && (
				<DateTimePicker
					onChange={(date)=>handleDatechange('toDate',date)}
					currentDate={toDate}
				></DateTimePicker>
			)} 

		</PanelBody>
	);
}
