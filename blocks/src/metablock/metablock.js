const { registerBlockType } = wp.blocks;
const { TextControl, DateTimePicker, BaseControl, TabPanel } = wp.components;

registerBlockType("cgb/meta-block", {
	title: "Meta Block",
	icon: "smiley",
	category: "common",

	attributes: {
		date: {
			type: "string",
			source: "meta",
			meta: "ku_date"
		},
		endDate: {
			type: "string",
			source: "meta",
			meta: "ku_end_date",
			default: "null/null"
		},
		open: {
			type: "string",
			default: "no"
		}
	},

	edit({ className, setAttributes, attributes }) {
		function updateDate(index, value) {
			let res = ["null", "null"];
			if (attributes.date) {
				res = attributes.date.split("/");
			}
			res[index] = value;
			stringRes = res[0] + "/" + res[1];
			setAttributes({ date: stringRes });
		}

		function getDate(index) {
			if (!attributes.date) {
				return null;
			}
			let res = attributes.date.split("/");
			return res[index] == "null" ? null : res[index];
		}
		function handleOpen(e) {
			if (attributes.open === e) {
				setAttributes({ open: "no" });
			} else {
				setAttributes({ open: e });
			}
		}

		return (
			<div className={className}>
				<div onClick={() => handleOpen("start")}>
					{getDate(0) || "NO STARTDATE"}
				</div>

				{attributes.open === "start" && (
					<BaseControl label="Start Date">
						<DateTimePicker
							currentDate={getDate(0)}
							onChange={e => updateDate(0, e)}
						/>
					</BaseControl>
				)}
				<div onClick={() => handleOpen("end")}>
					{getDate(1) || "NO ENDDATE"}
				</div>
				{attributes.open === "end" && (
					<DateTimePicker
						label="End Date"
						currentDate={getDate(1)}
						onChange={e => updateDate(1, e)}
					/>
				)}
			</div>
		);
	},

	// No information saved to the block
	// Data is saved to post meta via attributes
	save() {
		return null;
	}
});
