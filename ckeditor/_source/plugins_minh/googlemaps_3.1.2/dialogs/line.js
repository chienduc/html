
CKEDITOR.dialog.add( 'googlemapsLine', function( editor )
{
	var fldAreaProperties,
		mode;

	function pickAColor( button, field )
	{
		var thisDialog = button.getDialog();

		editor.openNestedDialog( 'colordialog',
			function( dialog ) // before showing
			{
				var color = thisDialog.getValueOf( 'Info', field),
					control = dialog.getContentElement( 'picker', 'selectedColor');
				// set initial value
				control.setValue( color );
				control.setInitValue();
			},
			function( dialog ) // on OK
			{
				var control = dialog.getContentElement( 'picker', 'selectedColor');

				thisDialog.getContentElement( 'Info', field ).setValue( control.getValue() );

				// Reset to empty default
				control.setValue( '' );
				control.setInitValue();
			});
	}

	return {
		title : editor.lang.googlemaps.properties,
		minWidth : 230,
		minHeight : 140,
		resizable : false,
		buttons : [ {
				type : 'button',
				id : 'deleteOverlay',
				label : editor.lang.googlemaps.deleteMarker,
				onClick : function( evt )
				{
					var dialog = evt.sender.getDialog();
					dialog.onRemoveOverlay();
					dialog.hide();
				}
		}, CKEDITOR.dialog.okButton, CKEDITOR.dialog.cancelButton ],

		onLoad: function(evt)
		{
			fldAreaProperties = this.getContentElement( 'Info', 'fldAreaProperties').getElement();

			this.setValues = function( data ) { this.definition.setValues.call(this, data); };
			this.getValues = function() { return this.definition.getValues.call(this); };
		},
		setValues : function(data)
		{
			var control = this.getContentElement( 'Info', 'cmbStrokeWeight');
			control.setValue( data.strokeWeight );
			control.setInitValue();
			control = this.getContentElement( 'Info', 'cmbStrokeOpacity');
			control.setValue( data.strokeOpacity );
			control.setInitValue();
			control = this.getContentElement( 'Info', 'txtStrokeColor');
			control.setValue( data.strokeColor );
			control.setInitValue();

			if (data.fillOpacity)
			{
				mode = 'polygon';
				fldAreaProperties.show();

				control = this.getContentElement( 'Info', 'cmbFillOpacity');
				control.setValue( data.fillOpacity );
				control.setInitValue();
				control = this.getContentElement( 'Info', 'txtFillColor');
				control.setValue( data.fillColor );
				control.setInitValue();
				this.resize(230, 240)
			}
			else
			{
				mode = 'polyline';
				fldAreaProperties.hide();
				this.resize(230, 140)
			}
		},

		getValues : function()
		{
			var data = {
				strokeWeight: this.getValueOf( 'Info', 'cmbStrokeWeight'),
				strokeOpacity: this.getValueOf( 'Info', 'cmbStrokeOpacity'),
				strokeColor: this.getValueOf( 'Info', 'txtStrokeColor')
			};
			if (mode == 'polygon')
			{
				data.fillOpacity = this.getValueOf( 'Info', 'cmbFillOpacity');
				data.fillColor = this.getValueOf( 'Info', 'txtFillColor');
			}

			return data;
		},

		contents : [
			{
				id : 'Info',
				elements :
				[
					{
						type: 'fieldset',
						id: 'fldLineProperties',
						label: editor.lang.googlemaps.lineProperties,
						children:
						[
							{
								id : 'cmbStrokeWeight',
								label: editor.lang.googlemaps.strokeWeight,
								labelLayout : 'horizontal',
								widths: [ '55px', '65px'],
								style : 'width:130px; margin-bottom:1em;',
								inputStyle : 'width:45px; ',
								type : 'select',
								items :
								[
									[ '1', '1'],
									[ '2', '2'],
									[ '3', '3'],
									[ '4', '4'],
									[ '5', '5'],
									[ '6', '6'],
									[ '7', '7'],
									[ '8', '8'],
									[ '9', '9'],
									[ '10', '10']
								]
							},
							{
								id : 'cmbStrokeOpacity',
								label: editor.lang.googlemaps.strokeOpacity,
								labelLayout : 'horizontal',
								widths: [ '55px', '65px'],
								style : 'width:130px; margin-bottom:1em;',
								inputStyle : 'width:45px; ',
								type : 'select',
								items :
								[
									[ '0.1', '0.1'],
									[ '0.2', '0.2'],
									[ '0.3', '0.3'],
									[ '0.4', '0.4'],
									[ '0.5', '0.5'],
									[ '0.6', '0.6'],
									[ '0.7', '0.7'],
									[ '0.8', '0.8'],
									[ '0.9', '0.9'],
									[ '1', '1']
								]
							},
							{
									type:'hbox',
									widths: [ '130px', '80px'],
									children:
									[
										{
											id : 'txtStrokeColor',
											type : 'text',
											label: editor.lang.googlemaps.strokeColor,
											labelLayout : 'horizontal',
											widths: [ '55px', '65px'],
											width: '55px'
										},
										{
											id : 'btnColor',
											type : 'button',
											label : editor.lang.googlemaps.chooseColor,
											onClick : function() {pickAColor( this, 'txtStrokeColor'); }
										}
									]
							}
						]
					},
					{
						type: 'fieldset',
						id: 'fldAreaProperties',
						label: editor.lang.googlemaps.areaProperties,
						children:
						[

							{
								id : 'cmbFillOpacity',
								label: editor.lang.googlemaps.fillOpacity,
								labelLayout : 'horizontal',
								widths: [ '55px', '65px'],
								style : 'width:130px; margin-bottom:1em;',
								inputStyle : 'width:45px; ',
								type : 'select',
								items :
								[
									[ '0.1', '0.1'],
									[ '0.2', '0.2'],
									[ '0.3', '0.3'],
									[ '0.4', '0.4'],
									[ '0.5', '0.5'],
									[ '0.6', '0.6'],
									[ '0.7', '0.7'],
									[ '0.8', '0.8'],
									[ '0.9', '0.9'],
									[ '1', '1']
								]
							},
							{
									type:'hbox',
									widths: [ '130px', '80px'],
									children:
									[
										{
											id : 'txtFillColor',
											type : 'text',
											label: editor.lang.googlemaps.fillColor,
											labelLayout : 'horizontal',
								widths: [ '55px', '65px'],
								style : 'width:130px; ',
											width: '55px'
										},
										{
											id : 'btnFillColor',
											type : 'button',
											label : editor.lang.googlemaps.chooseColor,
											onClick : function() {pickAColor( this, 'txtFillColor'); }
										}
									]
							}
						]
					}
				]
			}
		]
	};
} );
