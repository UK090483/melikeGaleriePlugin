const { PanelBody, DateTimePicker, BaseControl, FormToggle } = wp.components;

export default function PostDateControls(props) {
	const { fromDateActive, toDateActive, fromDate, toDate } = props.attributes;
	function handleFromDateChange(fromDate) {
		props.setAttributes({
			fromDate
		});
	}
	function handleFromDateToggle() {
		props.setAttributes({
			fromDateActive: !fromDateActive
		});
	}
	function handleToDateChange(toDate) {
		props.setAttributes({
			toDate
		});
	}
	function handleToDateToggle() {
		props.setAttributes({
			toDateActive: !toDateActive
		});
	}
	function handleNowFromChange() {
		let res = fromDate === "now" ? "" : "now";
		props.setAttributes({
			fromDate: res
		});
	}
	function handleNowToChange() {
		let res = toDate === "now" ? "" : "now";
		props.setAttributes({
			toDate: res
		});
	}

	function isNow(attr) {
		return props.attributes[attr] === "now";
	}

	return (
		<PanelBody title={"Date"} initialOpen={false}>
			<BaseControl label="From">
				<FormToggle
					checked={fromDateActive}
					onChange={handleFromDateToggle}
				></FormToggle>
			</BaseControl>

			{fromDateActive && (
				<BaseControl label="now">
					<FormToggle
						checked={isNow("fromDate")}
						onChange={handleNowFromChange}
					></FormToggle>
				</BaseControl>
			)}

			{fromDateActive && !isNow("fromDate") && (
				<DateTimePicker
					onChange={handleFromDateChange}
					currentDate={fromDate === "now" ? null : fromDate}
				></DateTimePicker>
			)}

			<hr style={{ borderTop: "5px solid" }}></hr>

			<BaseControl label="To">
				<FormToggle
					checked={toDateActive}
					onChange={handleToDateToggle}
				></FormToggle>
			</BaseControl>
			{toDateActive && (
				<BaseControl label="now">
					<FormToggle
						checked={isNow("toDate")}
						onChange={handleNowToChange}
					></FormToggle>
				</BaseControl>
			)}
			{toDateActive && !isNow("toDate") && (
				<DateTimePicker
					onChange={handleToDateChange}
					currentDate={props.attributes.toDate}
				></DateTimePicker>
			)}
		</PanelBody>
	);
}
