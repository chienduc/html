﻿
	CKEDITOR.dialog.add( 'googlemaps', function( editor )
	{
		var numbering = function( id ){ return id + CKEDITOR.tools.getNextNumber(); },
			GMapPreview = numbering( 'GMapPreview' ),
			btnAddNewMarker = numbering( 'btnAddNewMarker' ),
			btnAddNewLine = numbering( 'btnAddNewLine' ),
			btnAddNewArea = numbering( 'btnAddNewArea' ),
			btnAddNewText = numbering( 'btnAddNewText' ),
			btnKML = numbering( 'btnKML' ),
			txtText = numbering( 'txtText' ),
			COLORS = ["#880000", "#008800", "#000088","#888800", "#880088", "#008888", "000000", "888888"] ,
			colorIndex_ = -1 ,
			MarkerColors = ['green', 'purple', 'yellow', 'blue', 'orange', 'red'] ,
			iconColorIndex_ = -1 ,
			markers = [], lines = [], areas = [], texts = [] ,
			activeMarker = null, KmlOverlay = null, Mode = '',
			map = null,
			drawingManager,
			geocoder,
			allMapTypes,
			oParsedMap,
			oFakeImage,
			theDialog,
			internalUpdate = true; // true until the map is ready: no sync between dialog and map. A Mutex afterwards


		// FIX problems due to the CKEditor reset stylesheet
		var node = CKEDITOR.document.getHead().append( 'style' );
		node.setAttribute( "type", "text/css" );
		var content = "";
		content += "#" + GMapPreview + " * {white-space:normal; cursor:inherit; text-align:inherit;}";
		content += "#" + GMapPreview + " .Input_Text {cursor:text; border-style:inset; border-width:2px; margin-left:1em;}";
		content += "#" + GMapPreview + " .Input_Button { border-style:outset; border-width:2px; margin:0 1em;}";
		content += "#" + GMapPreview + " .Gmaps_Buttons { clear:both; text-align:center; margin-top:4px;}";
		content += "#" + GMapPreview + " .Gmaps_Options td { clear:both}";

		if ( CKEDITOR.env.ie )
			node.$.styleSheet.cssText = content;
		else
			node.$.innerHTML = content;


		// Define the overlay, derived from google.maps.OverlayView
		function Label(opt_options)
		{
			// Initialization
			this.setValues(opt_options);

			var marker = opt_options.marker;
			if (marker)
			{
				this.setValues({map:marker.getMap()});
				this.bindTo('position', marker, 'position');
				this.bindTo('text', marker, 'title');
			}

			// Label specific
			var span = this.span_ = document.createElement('span');
			span.style.cssText = 'white-space:nowrap; border:1px solid #999; padding:2px; background-color:white';
			if (opt_options.className)
				span.className = opt_options.className;

			var div = this.div_ = document.createElement('div');
			div.appendChild(span);
			div.style.cssText = 'position: absolute; display: none';
		}

	function InitializeLabels()
	{
		Label.prototype = new google.maps.OverlayView;

		// Implement onAdd
		Label.prototype.onAdd = function() {
		 var pane = this.getPanes().overlayLayer;
		 pane.appendChild(this.div_);

		 // Ensures the label is redrawn if the text or position is changed.
		 var me = this;
		 this.listeners_ = [
			 google.maps.event.addListener(this, 'position_changed',
					 function() { me.draw(); }),
			 google.maps.event.addListener(this, 'text_changed',
					 function() { me.draw(); })
		 ];
		};

		// Implement onRemove
		Label.prototype.onRemove = function() {
		 this.div_.parentNode.removeChild(this.div_);

		 // Label is removed from the map, stop updating its position/text.
		 for (var i = 0, I = this.listeners_.length; i < I; ++i) {
			 google.maps.event.removeListener(this.listeners_[i]);
		 }
		};

		// Implement draw
		Label.prototype.draw = function() {
		 var projection = this.getProjection();
		 var position = projection.fromLatLngToDivPixel(this.get('position'));

		 var div = this.div_;
		 div.style.left = position.x + 'px';
		 div.style.top = position.y + 'px';
		 div.style.display = 'block';

		 this.span_.innerHTML = this.get('text').toString();
		};
	}

	function initLoader()
	{
		if (window.google && window.google.load)
		{
			if (window.google.maps)
				initializeMaps();
			else
				loadMaps();
			return;
		}

		window['CKE_googleMaps_callback'] = function() { initializeMaps(); };

		// The Google AJAX loader seems to require an API key.
		// So we can't use client location
		var script = document.createElement("script");
		script.src = "//maps.googleapis.com/maps/api/js?sensor=false&libraries=drawing,geometry&callback=CKE_googleMaps_callback";
		script.type = "text/javascript";
		document.getElementsByTagName("head")[0].appendChild(script);
	}

	function loadMaps()
	{
		window.google.load("maps", "3",  {other_params:"sensor=false"});
		window.google.setOnLoadCallback(initializeMaps);
	}

	function initializeMaps()
	{
		window['CKE_googleMaps_callback'] = null;

		InitializeLabels();

		mapDiv = document.getElementById( GMapPreview );
		UpdateDimensions();

		allMapTypes = [google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.SATELLITE, google.maps.MapTypeId.HYBRID, google.maps.MapTypeId.TERRAIN] ;

		internalUpdate = true;

		var myLatlng = new google.maps.LatLng( oParsedMap.centerLat, oParsedMap.centerLon );
		var myOptions = {
		  zoom: parseInt(theDialog.getValueOf( 'Info', 'cmbZoom'), 10),
		  center: myLatlng,
				mapTypeId: allMapTypes[ oParsedMap.mapType ]
		};
		map = new google.maps.Map(mapDiv, myOptions);

        // Creates a drawing manager attached to the map that allows the user to draw
        // markers, lines, and shapes.
		var polyOptions = {editable: true};
        drawingManager = new google.maps.drawing.DrawingManager({
			drawingControl:false,
			polylineOptions: polyOptions,
			rectangleOptions: polyOptions,
			circleOptions: polyOptions,
			polygonOptions: polyOptions,
			map: map
		});

        google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) {

			if (e.type == google.maps.drawing.OverlayType.POLYLINE)
			{
				var polyline = e.overlay;
				polyline.OverlayType = 'polyline' ;
				polyline.text = '' ;
				polyline.maxWidth = 200 ;
				lines.push(polyline);
			}
			if (e.type == google.maps.drawing.OverlayType.POLYGON)
			{
				var polygon = e.overlay;
				polygon.OverlayType = 'polygon' ;
				polygon.text = '' ;
				polygon.maxWidth = 200 ;
				areas.push(polygon);
			}

            FinishEditOverlay();

        });



		setMapTypeControl(theDialog.getContentElement('Options', 'cmbMapTypes') );
		setZoomControl(theDialog.getContentElement('Options', 'cmbZoomControl') );
		setScaleControl(theDialog.getContentElement('Options', 'chkScale') );
		setOverviewControl(theDialog.getContentElement('Options', 'chkOverviewMap') );
		setOverviewControlOptions( oParsedMap.overviewMapControlOpened );

		// Load the data
		var i, markerPoints, point, linesData, line, polyline, areasData, area, polygon, textsData,
				points, aLinePoints, j, values;

		markers = []; lines = []; areas = []; texts = [] ;
		activeMarker = null; KmlOverlay = null; Mode = '';

		markerPoints = oParsedMap.markerPoints;
		for (i=0; i<markerPoints.length ; i++)
		{
			point = new google.maps.LatLng(parseFloat(markerPoints[i].lat), parseFloat(markerPoints[i].lon));
			AddMarkerAtPoint(point, markerPoints[i].text, markerPoints[i].color, markerPoints[i].title, markerPoints[i].maxWidth, false);
		}

		textsData = oParsedMap.textsData;
		for (i=0; i<textsData.length ; i++)
		{
			point = new google.maps.LatLng(parseFloat(textsData[i].lat), parseFloat(textsData[i].lon));
			AddTextAtPoint(point, textsData[i].title, textsData[i].className, false);
		}

		linesData = oParsedMap.linesData;
		for (i=0; i<linesData.length ; i++)
		{
			line = linesData[i];
			if (line.points)
				aLinePoints = google.maps.geometry.encoding.decodePath(line.points);
			else
			{
				// parsed from static:
				points = line.PointsData.split("|");
				aLinePoints = [];
				for(j=1; j<points.length; j++)
				{
					values = points[j].split(",");
					aLinePoints.push(new google.maps.LatLng(parseFloat(values[0]), parseFloat(values[1])) );
				}
			}
			polyline = new google.maps.Polyline( {map:map, path: aLinePoints, strokeColor: line.color, strokeOpacity: line.opacity, strokeWeight: line.weight, clickable:false} );

			polyline.text = line.text ;
			polyline.maxWidth = line.maxWidth ;
			polyline.OverlayType = 'polyline' ;
			lines.push(polyline);
		}

		areasData = oParsedMap.areasData;
		for (i=0; i<areasData.length ; i++)
		{
			area = areasData[i];
			line = area.polylines[0] ;
			polygon = new google.maps.Polygon( {map:map, paths: google.maps.geometry.encoding.decodePath(line.points), strokeColor: line.color, strokeOpacity: line.opacity, strokeWeight: line.weight, fillColor:area.color, fillOpacity:area.opacity, clickable:false} );
			polygon.text = area.text ;
			polygon.maxWidth = area.maxWidth ;
			polygon.OverlayType = 'polygon' ;
			areas.push(polygon);
		}

		onKmlChange();

		google.maps.event.addListener(map, 'click', function(point, b, c, d)
		{
			if (Mode == 'EditLine')
			{
				FinishEditOverlay();
				return;
			}

			if (Mode == 'AddMarker')
				AddMarkerAtPoint( point.latLng, editor.config.googleMaps_MarkerText || editor.lang.googlemaps.defaultMarkerText, getIconColor(), editor.lang.googlemaps.defaultTitle, 200, true );

			if (Mode == 'AddText')
				AddTextAtPoint( point.latLng, editor.lang.googlemaps.defaultTitle, 'MarkerTitle', true );
		});

		google.maps.event.addListener(map, 'zoom_changed', function()
		{
			if (internalUpdate)
				return;

			internalUpdate = true;
			theDialog.setValueOf('Info', 'cmbZoom', this.getZoom());
			internalUpdate = false;
		});

		internalUpdate = false;

		if (CKEDITOR.env.ie7Compat)
			fixIE7display();
	}

	// In IE7 or IE8 with compatibility mode, the content just dissapears on initial load, as well as
	// while hovering the dialog buttons.
	function fixIE7display()
	{
		var w = document.getElementById(GMapPreview);
		w.style.position = "";
		w.parentNode.style.position = "relative";
		window.setTimeout( function() { w.style.position = "relative"; w.parentNode.style.position = "";}, 0);
	}

	function UpdateDimensions()
	{
		mapDiv.style.width = theDialog.getValueOf( 'Info', 'txtWidth') + 'px' ;
		mapDiv.style.height = theDialog.getValueOf( 'Info', 'txtHeight') + 'px' ;

		if (map)
			google.maps.event.trigger(map, 'resize');
	}

	function setMapTypeControl(obj)
	{
		var style;
		switch (obj.getValue())
		{
			case 'None':
				style = 'None';
				break;
			case 'Default':
				style = google.maps.MapTypeControlStyle.DEFAULT;
				break;
			case 'Full':
				style = google.maps.MapTypeControlStyle.HORIZONTAL_BAR;
				break;
			case 'Menu':
				style = google.maps.MapTypeControlStyle.DROPDOWN_MENU;
				break;
		}

		if (style=='None')
			map.setOptions({ mapTypeControl:false });
		else
			map.setOptions({ mapTypeControl:true, mapTypeControlOptions : {style: style} });
	}

	function setZoomControl(obj)
	{
		var style;
		switch (obj.getValue())
		{
			case 'None':
				style = 'None';
				break;
			case 'Default':
				style = google.maps.NavigationControlStyle.DEFAULT;
				break;
			case 'Full':
				style = google.maps.NavigationControlStyle.ZOOM_PAN;
				break;
			case 'Small':
				style = google.maps.NavigationControlStyle.SMALL;
				break;
		}

		if (style=='None')
			map.setOptions({ navigationControl:false });
		else
			map.setOptions({ navigationControl:true, navigationControlOptions : {style: style} });
	}

	function setScaleControl(obj)
	{
		map.setOptions({ scaleControl: obj.getValue() });
	}

	function setOverviewControl(obj)
	{
		map.setOptions({ overviewMapControl: obj.getValue() });
	}

	function setOverviewControlOptions( opened )
	{
		map.setOptions({ overviewMapControlOptions: {opened: opened} });
	}

	function getMapTypeIndex()
	{
		return CKEDITOR.tools.indexOf( allMapTypes, map.getMapTypeId() );
	}

	function searchAddress()
	{
		var address = theDialog.getValueOf('Info', 'searchDirection');

		if (!geocoder)
			geocoder = new google.maps.Geocoder();

		geocoder.geocode( {'address': address}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK)
			{
				map.setCenter( results[0].geometry.location );
				AddMarkerAtPoint( results[0].geometry.location, address, getIconColor(), editor.lang.googlemaps.defaultTitle, 200, false );
			}
			else
			  alert("Geocode was not successful for the following reason: " + status);
	  });
	}

	function getColor()
	{
		colorIndex_ += 1;
		return COLORS[colorIndex_ % COLORS.length] ;
	}

	function getIconColor()
	{
		iconColorIndex_ += 1;
		return MarkerColors[iconColorIndex_ % MarkerColors.length] ;
	}

	function getIcon(color)
	{
		return '//maps.gstatic.com/mapfiles/ms/micons/' + color + '-dot.png' ;
	}

	function getTextIcon()
	{
		return editor.plugins.googlemaps.path + 'images/TextIcon.png' ;
	}

	// Change mode to enable addition of new elements.
	function setMode( newMode )
	{
		FinishEditOverlay();

		// toggle
		if ( Mode == newMode )
		{
			Mode = '' ;
			return ;
		}

		Mode = newMode ;
		switch (Mode)
		{
			case 'AddMarker':
				changeImage( btnAddNewMarker, 'AddMarkerDown.png' );
				// Change cursor type
				setMapCursor('crosshair');
				break;
			case 'AddLine':
				changeImage( btnAddNewLine, 'AddLineDown.png' );

				var polylineOptions = drawingManager.get('polylineOptions');
				polylineOptions.strokeColor = getColor(false);
				polylineOptions.strokeOpacity = 0.8;
				polylineOptions.strokeWeight = 4;
				drawingManager.set('polylineOptions', polylineOptions);

				drawingManager.setDrawingMode( google.maps.drawing.OverlayType.POLYLINE );
				break;

			case 'AddArea':
				changeImage( btnAddNewArea, 'AddAreaDown.png' );

				var polygonOptions = drawingManager.get('polygonOptions');
				polygonOptions.strokeColor = getColor(false);
				polygonOptions.strokeOpacity = 0.7;
				polygonOptions.strokeWeight = 2;
				polygonOptions.fillColor = polygonOptions.strokeColor;
				polygonOptions.fillOpacity = 0.2;
				drawingManager.set('polygonOptions', polygonOptions);

				drawingManager.setDrawingMode( google.maps.drawing.OverlayType.POLYGON );
				break;

			case 'AddText':
				changeImage( btnAddNewText, 'AddTextDown.png' );
				// Change cursor type
				setMapCursor('crosshair');
				break;
		}

	}

	var selectedShape;
	function clearSelection()
	{
        if (selectedShape) {
			selectedShape.setEditable(false);
			selectedShape = null;
		}
	}

	// polylines and polygons. Check the OverlayType property.
	function EnableOverlayEditing(overlay)
	{
		overlay.setOptions( {clickable: true} );

		google.maps.event.addListener(overlay, "mouseover", function()
		{
			if ( overlay != selectedShape )
			{
				setMode('EditLine');
				selectedShape = overlay;
				overlay.setEditable(true);
			}
		});

		google.maps.event.addListener(overlay, "click", function(latlng)
		{
			/* FIXME, if enableEditing works, this should get a parameter stating the index of the vertex
			if (typeof index == "number") {
				// if only 2 points left, remove the line.
				var max = (overlay.OverlayType == 'polyline') ? 2 : 4 ;
				if (overlay.getVertexCount() == max)
				{
					overlay.disableEditing();

					// remove the overlay:
					if (overlay.OverlayType == 'polyline')
						lines.splice(lines.indexOf(overlay), 1);
					else
						areas.splice(areas.indexOf(overlay), 1);

					map.removeOverlay(overlay);
					return;
				}
				overlay.deleteVertex(index);
			} else {
			*/
					activeMarker = overlay ;

					editor.openNestedDialog( 'googlemapsLine',
						function (dialog)
						{
							var obj = activeMarker;
							var data = {strokeColor: obj.strokeColor, strokeOpacity: obj.strokeOpacity.toFixed(1), strokeWeight: obj.strokeWeight};
							if (activeMarker.OverlayType=='polygon')
							{
								data.fillColor = obj.fillColor;
								data.fillOpacity = obj.fillOpacity.toFixed(1);
							}
							dialog.setValues( data );
							dialog.onRemoveOverlay = DeleteCurrentOverlay;
						},

						function (dialog)
						{
							var data = dialog.getValues();
							activeMarker.setOptions( data );
						}
					);

			/*		} EnableEditing */
		});
	}

	function DisableOverlayEditing(overlay)
	{
		overlay.setOptions( {clickable: false});
		google.maps.event.clearListeners(overlay, "click");
		google.maps.event.clearListeners(overlay, "mouseover");
		google.maps.event.clearListeners(overlay, "mouseout");
	}

	function setMapCursor(cursor)
	{
		map.setOptions({draggableCursor:cursor});
	}

	function AddMarkerAtPoint( point, text, color, title, maxWidth, interactive )
	{
		var marker = new google.maps.Marker( {position:point, map:map, title: title, icon: getIcon(color), draggable: true }) ,
			label ;
		marker.text = text ;
		marker.color = color;
		marker.title = title ;
		marker.maxWidth = maxWidth ;

		if ( title !== "" )
		{
			label = new Label({marker:marker, className: 'MarkerTitle'});
			marker.label = label ;
		}

		google.maps.event.addListener(marker, 'click', function() {
			EditMarker(this);
		});

		markers.push( marker );
		FinishEditOverlay();

		if (interactive)
			EditMarker( marker );
		else
			marker.setOptions( {draggable:false} );
	}

	function OpenInfoWindow( obj, text )
	{
		if (!obj._infoWindow)
		{
			obj._infoWindow = new google.maps.InfoWindow({maxWidth:obj.maxWidth});
		}

		obj._infoWindow.setContent( text );
		obj._infoWindow.open(map, obj);
	}

	function EditMarker( obj )
	{
		if (!obj.getDraggable())
		{
			OpenInfoWindow(obj, '<div class="InfoContent">' + obj.text + '</div>');
			return;
		}

		// We are really editing.
		activeMarker = obj ;
		Mode = 'EditMarker' ;

		editor.openNestedDialog( 'googlemapsMarker', prepareMarkerTextsDialog, readMarkerTextsDialog );
	}

	function prepareMarkerTextsDialog( dialog )
	{
		var obj = activeMarker;
		dialog.setValues( {title: obj.getTitle(), maxWidth: obj.maxWidth, text: obj.text, color: obj.color} );
		dialog.onRemoveMarker = DeleteCurrentMarker;
	}

	function readMarkerTextsDialog( dialog )
	{
		var data = dialog.getValues();

		activeMarker.text = data.text ;
		activeMarker.maxWidth = data.maxWidth ;

		var title = data.title,
			label = activeMarker.label ;
		activeMarker.setTitle( title );

		activeMarker.color = data.color ;
		activeMarker.setIcon( getIcon(data.color) );

		if ( title !== "" )
		{
			if ( !label )
			{
				label = new Label({marker:activeMarker, className: 'MarkerTitle'});
				activeMarker.label = label ;
			}
		}
		else
		{
			if ( label )
			{
				label.setMap( null );
				activeMarker.label = null ;
			}
		}
	}

	function DeleteCurrentMarker()
	{
		// Remove it from the global array
		for ( var j = 0 ; j < markers.length ; j++ )
		{
			if ( markers[j] == activeMarker)
			{
				markers.splice(j, 1);
				break ;
			}
		}
		if ( activeMarker.label )
		{
			activeMarker.label.setMap( null );
			activeMarker.label = null ;
		}
		// Remove it from the map
		activeMarker.setMap( null );

		// this will reset activeMarker
		FinishedEditing();
	}


	function DeleteCurrentOverlay()
	{
		var group;
		if (activeMarker.OverlayType=='polygon')
			group = areas;
		else
			group = lines;

		// Remove it from the global array
		for ( var j = 0 ; j < group.length ; j++ )
		{
			if ( group[j] == activeMarker)
			{
				group.splice(j, 1);
				break ;
			}
		}
		// Remove it from the map
		activeMarker.setMap( null );

		// this will reset activeMarker
		FinishedEditing();
	}

	function FinishedEditing()
	{
		Mode = '' ;
		activeMarker = null ;
	}

	function AddTextAtPoint( point, text, className, interactive )
	{
		var marker = new google.maps.Marker( {position:point, map:map, icon: getTextIcon(), draggable: true }) ,
			label ;
		marker.title = text ;
		marker.className = className ;

		if ( text !== "" )
		{
			label = new Label({marker:marker, className: className});
			marker.label = label ;
		}

		google.maps.event.addListener(marker, 'click', function() {
			EditText(this);
		});

		texts.push( marker );
		FinishEditOverlay();

		if (interactive)
			EditText( marker );
		else
			marker.setVisible(false);
	}

	function EditText(obj)
	{
		if (!obj.getDraggable())
			return;

		// We are really editing.
		activeMarker = obj ;
		Mode = 'EditText' ;

		editor.openNestedDialog( 'googlemapsText',
			function (dialog) { dialog.setValues( {title:obj.title});},
			updateTextMarker );
	}

	function updateTextMarker( dialog )
	{
		var data = dialog.getValues();

		var title = data.title,
			label = activeMarker.label ;
		activeMarker.setTitle( title );

		if ( title !== "" )
		{
			if ( !label )
			{
				label = new Label( {marker:activeMarker, className: 'MarkerTitle'} );
				activeMarker.label = label ;
			}
		}
		else
		{
			DeleteCurrentText();
		}

		FinishedEditing();
	}

	function DeleteCurrentText()
	{
		// Remove it from the global array
		for ( var j = 0 ; j < texts.length ; j++ )
		{
			if ( texts[j] == activeMarker)
			{
					texts.splice(j, 1);
					break ;
			}
		}
		if ( activeMarker.label )
		{
			activeMarker.label.setMap( null  );
			activeMarker.label = null ;
		}
		// Remove it from the map
		activeMarker.setMap( null );
	}

	function changeImage(id, pic)
	{
		CKEDITOR.document.$.getElementById( id ).src = editor.plugins.googlemaps.path + 'images/' + pic;
	}

	function FinishEditOverlay()
	{
		// Switch back to non-drawing mode after drawing a shape.
		if (drawingManager) drawingManager.setDrawingMode(null);
		clearSelection();

		if (Mode==='')
			return;

		var line, area ;
		switch (Mode)
		{
			case 'AddLine':
				changeImage( btnAddNewLine, 'AddLine.png');
				line = lines[lines.length - 1] ;
				if (line)
				{
					line.setEditable(false);
					EnableOverlayEditing(line);
				}
				Mode = '';
				break;

			case 'AddArea':
				changeImage( btnAddNewArea, 'AddArea.png');
				area = areas[areas.length - 1] ;
				if (area)
				{
					area.setEditable(false);
					EnableOverlayEditing(area);
				}
				Mode = '';
				break;

			case 'AddMarker':
				changeImage( btnAddNewMarker, 'AddMarker.png');
				// Change cursor type
				setMapCursor('');

				break ;

			case 'AddText':
				changeImage( btnAddNewText, 'AddText.png');
				// Change cursor type
				setMapCursor('');
				break ;

			case 'EditMarker':
				FinishedEditing();
				break;

			case 'EditText':
				FinishedEditing();
				break;

			case 'EditLine':
				FinishedEditing();
				break;

			default:
				break;
		}
	}

	function DisableEditing()
	{
		var i;
		for(i=0; i<markers.length; i++)
			markers[i].setOptions({draggable:false});

		for(i=0; i<texts.length; i++)
			texts[i].setVisible(false);

		for(i=0; i<lines.length; i++)
			DisableOverlayEditing( lines[i] );

		for(i=0; i<areas.length; i++)
			DisableOverlayEditing( areas[i] );
	}

	function EnableEditing()
	{
		var i;

		for(i=0; i<markers.length; i++)
			markers[i].setOptions({draggable:true});

		for(i=0; i<texts.length; i++)
			texts[i].setVisible(true);

		for(i=0; i<lines.length; i++)
			EnableOverlayEditing( lines[i] );

		for(i=0; i<areas.length; i++)
			EnableOverlayEditing( areas[i] );
	}

	function EncodeLineData(polyline)
	{
		var o = {color : polyline.strokeColor, opacity : polyline.strokeOpacity, weight : polyline.strokeWeight},
			path = polyline.getPath(),
			length = path.getLength();

		if (typeof(polyline.text) !== 'undefined')
			o.text = polyline.text;
		if (typeof(polyline.maxWidth) !== 'undefined')
			o.maxWidth = polyline.maxWidth;

		if (length<2)
			return null;

		o.points = google.maps.geometry.encoding.encodePath(path);

		return o;
	}

	function resolveUrl(url)
	{
		// Resolve the url so it becomes a full URL including the host
		var img = document.createElement("IMG");
		img.src = url;
		url = img.src;
		img = null;
		return url;
	}

	function onKmlChange(fileUrl, data)
	{
		var url = (fileUrl && typeof(fileUrl)=="string") ? fileUrl : theDialog.getValueOf('Elements', 'txtKMLUrl');

		if (KmlOverlay)
		{
			if (KmlOverlay.url == url)
				return;

			KmlOverlay.setMap( null );
			KmlOverlay = null ;
		}
		if ( !url )
			return ;

		var match = /:\/\/(.*?)\//.exec(url);
		if (!match)
		{
			url = resolveUrl(url);
			match = /:\/\/(.*?)\//.exec(url);
		}
		if (match[1].indexOf('.')==-1)
		{
			// The function is called while CKFinder is open, so we have to delay it
			window.setTimeout( function() {
				alert("Error: You must provide a public server in the url of KML files.");
			}, 500);
			return false;
		}
		KmlOverlay = new google.maps.KmlLayer(url, {map:map});
	}

		return {
			title : editor.lang.googlemaps.title,
			minWidth : 500,
			minHeight : 460,
			onLoad : function()
			{
				theDialog = this;

				// Act on tab switching
				theDialog.on('selectPage', function (e)
					{
						if (CKEDITOR.env.ie7Compat)
							fixIE7display();

						if (e.data.page=='Elements')
						{
							EnableEditing();
						}
						else
						{
							FinishEditOverlay();
							DisableEditing();
						}
					});

				// Adjust the contents so they take only the top of the dialog and add a bottom div
				// that will show the map in all the tabs
				var contents = this.parts.contents;
				var children = contents.getChildren();
				for(var i=children.count()-1; i>=0; i--)
				{
					var div = children.getItem(i);
					div.$.style.height = '64px';
				}
				contents.appendHtml('<div id="Wrapper' + GMapPreview + '" style="width:500px; height:370px; overflow:auto;"><div id="' + GMapPreview + '" style="outline:0;"></div></div>');

				// It flickers, but at least the map doesn't go away
				// I can't find any other solution for the moment.
				if (CKEDITOR.env.ie7Compat)
				{
					this.parts.footer.on( 'mousemove', fixIE7display);
					this.parts.footer.on( 'mouseleave', function() { window.setTimeout(fixIE7display, 0);} );
				}
			},
			onShow : function()
			{
				Mode = '';
				internalUpdate = true;
				oFakeImage = null;
				oParsedMap = null;
				var handler = editor.plugins.googleMapsHandler;

				// Try to detect a map
				var fakeImage = this.getSelectedElement();

				if ( fakeImage )
				{
					oFakeImage = fakeImage.$;
					if (fakeImage.getAttribute( 'mapnumber' ))
					{
						oParsedMap = handler.getMap( oFakeImage.getAttribute( 'mapnumber' ) );
						oParsedMap && oParsedMap.updateDimensions( oFakeImage );
					}
					if (!oParsedMap)
					{
						if (!handler.isStaticImage( oFakeImage ))
							oFakeImage = null ;
						else
						{
							oParsedMap = handler.createNew();
							oParsedMap.parseStaticMap2( oFakeImage );
							// this way it will be recreated,
							// first we select the first div that we usually create and select.
							// then on OK the structure will be recreated overwriting the existing one
							if (oFakeImage.parentNode.nodeName=='DIV' && !oFakeImage.previousSibling && !oFakeImage.nextSibling)
							{
								oFakeImage = oFakeImage.parentNode ;
								if (editor.config.googleMaps_WrapperClass && oFakeImage.parentNode.nodeName=='DIV' && oFakeImage.parentNode.className==editor.config.googleMaps_WrapperClass)
									oFakeImage = oFakeImage.parentNode ;

								editor.getSelection().selectElement( new CKEDITOR.dom.element( oFakeImage ) );
							}
							oFakeImage = null ;
						}
					}
				}

				if ( !oParsedMap )
				{
					oParsedMap = handler.createNew();

					// Try W3C Geolocation if we are creating a new map and no default has been set
					if (navigator.geolocation && !editor.config.googleMaps_CenterLat)
					{
						navigator.geolocation.getCurrentPosition( function(position) {
							oParsedMap.centerLat = position.coords.latitude.toFixed(5);
							oParsedMap.centerLon = position.coords.longitude.toFixed(5);

							map.setCenter( new google.maps.LatLng(oParsedMap.centerLat, oParsedMap.centerLon) );
						});
					}
				}

				theDialog.setValueOf( 'Info', 'txtWidth', oParsedMap.width);
				theDialog.setValueOf( 'Info', 'txtHeight', oParsedMap.height);

				theDialog.setValueOf( 'Info', 'cmbZoom', oParsedMap.zoom);

				theDialog.setValueOf( 'Options', 'cmbGeneratedType', oParsedMap.generatedType);
				theDialog.setValueOf( 'Options', 'cmbZoomControl', oParsedMap.zoomControl);
				theDialog.setValueOf( 'Options', 'cmbMapTypes', oParsedMap.mapTypeControl);
				theDialog.setValueOf( 'Options', 'chkScale', oParsedMap.scaleControl);
				theDialog.setValueOf( 'Options', 'chkOverviewMap', oParsedMap.overviewMapControl);

				theDialog.setValueOf( 'Elements', 'txtKMLUrl', oParsedMap.kmlOverlay);

				// Init the map
				initLoader();
			},
			onOk : function()
			{
				oParsedMap.width = theDialog.getValueOf('Info', 'txtWidth');
				oParsedMap.height = theDialog.getValueOf('Info', 'txtHeight');

				oParsedMap.zoom = parseInt(theDialog.getValueOf('Info', 'cmbZoom'), 10);

				var point = map.getCenter();
				oParsedMap.centerLat = point.lat().toFixed(5);
				oParsedMap.centerLon = point.lng().toFixed(5);

				oParsedMap.mapType = getMapTypeIndex();

				oParsedMap.generatedType = parseInt(theDialog.getValueOf('Options', 'cmbGeneratedType'), 10);
				oParsedMap.zoomControl = theDialog.getValueOf('Options', 'cmbZoomControl');
				oParsedMap.mapTypeControl = theDialog.getValueOf('Options', 'cmbMapTypes');
				oParsedMap.scaleControl = theDialog.getValueOf('Options', 'chkScale');

				oParsedMap.overviewMapControl = theDialog.getValueOf('Options', 'chkOverviewMap');
				oParsedMap.overviewMapControlOpened = map.overviewMapControlOptions.opened;
				oParsedMap.googleBar = theDialog.getValueOf('Options', 'chkGoogleBar');

				var markerPoints = [],
					textsData = [],
					linesData = [], line,
					areasData = [], area,
					i;

				for (i=0; i<markers.length ; i++)
				{
					point = markers[i].getPosition();
					markerPoints.push({lat: point.lat().toFixed(5), lon: point.lng().toFixed(5), text:markers[i].text, color:markers[i].color, title:markers[i].title, maxWidth:markers[i].maxWidth});
				}
				oParsedMap.markerPoints = markerPoints ;

				for (i=0; i<texts.length ; i++)
				{
					point = texts[i].getPosition();
					textsData.push({lat: point.lat().toFixed(5), lon: point.lng().toFixed(5), title:texts[i].title, className:texts[i].className});
				}
				oParsedMap.textsData = textsData ;

				for (i=0; i<lines.length ; i++)
				{
					line = EncodeLineData(lines[i]);
					if (line)
						linesData.push( line );
				}
				oParsedMap.linesData = linesData ;

				for (i=0; i<areas.length ; i++)
				{
					area = {polylines:[]};
					// find polylines
					area.polylines.push( EncodeLineData( areas[i] ) );
					if (area.polylines[0])
					{
						area.color = areas[i].fillColor;
						area.opacity = areas[i].fillOpacity;
						area.text = areas[i].text;
						area.maxWidth = areas[i].maxWidth;
						areasData.push( area );
					}
				}
				oParsedMap.areasData = areasData ;

				oParsedMap.kmlOverlay = theDialog.getValueOf('Elements', 'txtKMLUrl');

				if ( !oFakeImage )
					oFakeImage = oParsedMap.createHtmlElement();

				oParsedMap.updateHTMLElement( oFakeImage );
			},

			onHide : function()
			{
				// Destroy map. V3 doesn't have a "map.unload()"
				for (i=0; i<markers.length ; i++)
				{
					var marker = markers[i];
					marker.setMap( null );
					if (marker.label)
					{
						marker.label.setMap(null);
						marker.label = null;
					}
				}

				for (i=0; i<texts.length ; i++)
				{
					var marker = texts[i] ;
					marker.setMap( null );
					if (marker.label)
					{
						marker.label.setMap(null);
						marker.label = null;
					}
				}

				for (i=0; i<lines.length ; i++)
				{
					lines[i].setMap( null );
				}

				for (i=0; i<areas.length ; i++)
				{
					areas[i].setMap( null );
				}

				if (KmlOverlay)
				{
					KmlOverlay.setMap( null );
					KmlOverlay = null ;
				}

				google.maps.event.clearInstanceListeners( map );
				map = null;

//				It causes an error in IE when the window is resized (or the editor maximized) afterwards
//				var mapDiv = document.getElementById( GMapPreview );
//				mapDiv.innerHTML = '';

				internalUpdate = true;
			},

			contents : [
				{
					id : 'Info',
					label : editor.lang.googlemaps.map,
					elements :
					[
						{
							type : 'hbox',
							widths : [ '105px', '105px', '140px', '120px' ],
							children :
							[
								{
									id : 'txtWidth',
									type : 'text',
										widths : [ '55px', '50px'],
										width: '30px',
									labelLayout : 'horizontal',
									label : editor.lang.googlemaps.width,
									onBlur : UpdateDimensions,
									required : true
								},
								{
									id : 'txtHeight',
									type : 'text',
										widths : [ '55px', '50px'],
										width: '30px',
									labelLayout : 'horizontal',
									label : editor.lang.googlemaps.height,
									onBlur : UpdateDimensions,
									required : true
								},
								{
									id : 'cmbZoom',
									type : 'select',
									widths : [ '110px', '30px'],
									controlStyle : 'width:4em',
									hidden : true,
									labelLayout : 'horizontal',
									label : editor.lang.googlemaps.zoomLevel,
									onChange : function( o )
									{
										if (internalUpdate)
											return;

										internalUpdate = true;
										map.setZoom(parseInt(this.getValue(), 10));
										internalUpdate = false;
									},
									items :
									[
										[ '0', '0'],
										[ '1', '1'],
										[ '2', '2'],
										[ '3', '3'],
										[ '4', '4'],
										[ '5', '5'],
										[ '6', '6'],
										[ '7', '7'],
										[ '8', '8'],
										[ '9', '9'],
										[ '10', '10'],
										[ '11', '11'],
										[ '12', '12'],
										[ '13', '13'],
										[ '14', '14'],
										[ '15', '15'],
										[ '16', '16'],
										[ '17', '17'],
										[ '18', '18'],
										[ '19', '19'],
										[ '20', '20'],
										[ '21', '21'],
										[ '22   ', '22']
									]
								},
								{ // Padding at the right
									type : 'html',
									html : '<div> </div>'
								}
							]
						},
						{
							type : 'hbox',
							widths : [ '340px', '100px', '' ],
							children :
							[
								{
									id : 'searchDirection',
									type : 'text',
									label : editor.lang.googlemaps.searchDirection,
									labelLayout : 'horizontal',
									onKeyup: function( evt )
									{
										if ( evt.data.getKeystroke() == 13 )
										{
											searchAddress();
											evt.stop();
											return false;
										}
									}
								},
								{
									id : 'btnSearch',
									type : 'button',
									align : 'center',
									label : editor.lang.googlemaps.search,
									onClick : searchAddress
								}
							]
						}
					]
				},
				{
					id : 'Options',
					label : editor.lang.googlemaps.options,
					elements :
					[
						{
							type : 'hbox',
							widths : [ '180px', '160px', '100px', '25px' ],
							children :
							[
								{
									id : 'cmbGeneratedType',
									type : 'select',
									labelLayout : 'horizontal',
									label : editor.lang.googlemaps.loadMap,
									items :
									[
										[ editor.lang.googlemaps.onlyStatic, '1'],
										[ editor.lang.googlemaps.onClick, '2'],
										[ editor.lang.googlemaps.onLoad, '3']
									]
								},
								{
									id : 'cmbZoomControl',
									type : 'select',
									labelLayout : 'horizontal',
									label : editor.lang.googlemaps.zoomControl,
									items :
									[
										[ editor.lang.googlemaps.none, 'None'],
										[ editor.lang.googlemaps.Default, 'Default'],
										[ editor.lang.googlemaps.smallZoom, 'Small'],
										[ editor.lang.googlemaps.fullZoom, 'Full']
									],
									onChange : function(o) {
										if (internalUpdate)
											return;
										internalUpdate = true;

										setZoomControl(this);

										internalUpdate = false;
									}
								},
								{
									id : 'chkGoogleBar',
									type : 'checkbox',
									labelLayout : 'horizontal',
									label : editor.lang.googlemaps.googleBar,
										hidden: true,
									onChange : function(o) {

									}
								},
								{ // Padding at the right
									type : 'html',
									html : '<div> </div>'
								}
							]
						},
						{
							type : 'hbox',
							widths : [ '180px', '160px', '100px', '25px' ],
							children :
							[
								{
									id : 'cmbMapTypes',
									type : 'select',
									labelLayout : 'horizontal',
									label : editor.lang.googlemaps.mapTypes,
									onChange : function(o) {
										if (internalUpdate)
											return;
										internalUpdate = true;

										setMapTypeControl(this);

										internalUpdate = false;
									},
									items :
									[
										[ editor.lang.googlemaps.none, 'None'],
										[ editor.lang.googlemaps.Default, 'Default'],
										[ editor.lang.googlemaps.mapTypesFull, 'Full'],
										[ editor.lang.googlemaps.mapTypesMenu, 'Menu']
									]
								},
								{
									id : 'chkScale',
									type : 'checkbox',
									labelLayout : 'horizontal',
									label : editor.lang.googlemaps.scale,
									onChange : function(o) {
										if (internalUpdate)
											return;

										setScaleControl(this);
									}
								},
								{
									id : 'chkOverviewMap',
									type : 'checkbox',
									labelLayout : 'horizontal',
									label : editor.lang.googlemaps.overview,
									onChange : function(o) {
										if (internalUpdate)
											return;

										setOverviewControl(this);
									}
								},
								{ // Padding at the right
									type : 'html',
									html : '<div> </div>'
								}
							]
						}
					]
				},
				{
					id : 'Elements',
					label : editor.lang.googlemaps.elements,
					elements :
					[
						{
							type : 'hbox',
							widths : [ '26px', '26px', '26px', '26px', '26px', '338px' ],
							children :
							[
								{
									type : 'html',
									onClick: function() { setMode('AddMarker'); },
									html : '<div>'+
										'<img tabindex="-1" title="' + editor.lang.googlemaps.addMarker + '" alt="' + editor.lang.googlemaps.addMarker + '"' +
										' style="cursor:pointer"' +
										' id="' + btnAddNewMarker + '" src="' + editor.plugins.googlemaps.path + 'images/AddMarker.png">' +
										'</div>'
								},
								{
									type : 'html',
									onClick: function() { setMode('AddLine'); },
									html : '<div>'+
										'<img tabindex="-1" title="' + editor.lang.googlemaps.addLine + '" alt="' + editor.lang.googlemaps.addLine + '"' +
										' style="cursor:pointer"' +
										' id="' + btnAddNewLine + '" src="' + editor.plugins.googlemaps.path + 'images/AddLine.png">' +
										'</div>'
								},
								{
									type : 'html',
									onClick: function() { setMode('AddArea'); },
									html : '<div>'+
										'<img tabindex="-1" title="' + editor.lang.googlemaps.addArea + '" alt="' + editor.lang.googlemaps.addArea + '"' +
										' style="cursor:pointer"' +
										' id="' + btnAddNewArea + '" src="' + editor.plugins.googlemaps.path + 'images/AddArea.png">' +
										'</div>'
								},
								{
									type : 'html',
									onClick: function() { setMode('AddText'); },
									html : '<div>'+
										'<img tabindex="-1" title="' + editor.lang.googlemaps.addText + '" alt="' + editor.lang.googlemaps.addText + '"' +
										' style="cursor:pointer"' +
										' id="' + btnAddNewText + '" src="' + editor.plugins.googlemaps.path + 'images/AddText.png">' +
										'</div>'
								},
								{
									type : 'html',
									onClick: function() {
										// Toggle visibility
										var div = theDialog.getContentElement('Elements', 'KMLContainer').getElement();

										if (div.$.style.display == 'none' )
											div.show();
										else
											div.hide();
									},
									html : '<div>'+
										'<img tabindex="-1" title="' + editor.lang.googlemaps.addKML + '" alt="' + editor.lang.googlemaps.addKML + '"' +
										' style="cursor:pointer"' +
										' id="' + btnKML + '" src="' + editor.plugins.googlemaps.path + 'images/KML.png">' +
										'</div>'
								},
								{ //padding at the right
									type : 'html',
									html : '<div>&nbsp;</div>'
								}
							]
						},
						{
							id : 'KMLContainer',
							type : 'hbox',
							widths : [ '370px' ],
							hidden : true,
							children :
							[
								{
									id : 'txtKMLUrl',
									type : 'text',
									labelLayout : 'horizontal',
									label : editor.lang.googlemaps.kmlUrl,
									onBlur: onKmlChange,
									onKeyup: function( evt )
										{
											if ( evt.data.getKeystroke() == 13 )
											{
												onKmlChange();
												evt.stop();
												return false;
											}
										}
								},
								{
									type : 'button',
									id : 'browse',
									hidden : true,
									filebrowser :
									{
										action : 'Browse',
										target: 'Elements:txtKMLUrl',
										url: editor.config.filebrowserKmlBrowseUrl || editor.config.filebrowserBrowseUrl,
										onSelect : onKmlChange
									},
									label : editor.lang.common.browseServer
								}
							]
						}
					]
				}
			]
		};
	} );
