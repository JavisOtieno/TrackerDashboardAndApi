$(function(e) {
    "use strict";

    //______Basic Data Table
    var tableAllocate=$('#basic-datatable').DataTable({
        language: {
            searchPlaceholder: 'Search...',
            sSearch: '',
        },
        "order": [0, 'desc'],
        'ordering': true,
    });

    $('#routeplan_user_id').on('change', function() {
        var selectedOption = $(this).val();
        if (selectedOption) {
            fetchTableData(selectedOption);
        } else {
            table.clear().draw();
        }
    });

    function fetchTableData(option) {
        $.ajax({
            
            url: '/updateoutletscreaterouteplan/' + option, // Replace with your actual route to fetch data
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                updateDataTable(response); // Call function to update DataTable
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', error);
            }
        });
    }

    function updateDataTable(data) {
        // Clear existing DataTable rows
        tableAllocate.clear();
        //alert("we are here");

        // console.log("data");
        // console.log(data);
        // console.log("data");

        if (Array.isArray(data.outlets) && data.outlets.length > 0) {
        // Add new rows based on fetched data
        $.each(data.outlets, function(index, outlet) {

            console.log('outlet');
            console.log(outlet);
            console.log('outlet');
            
            tableAllocate.row.add([
                outlet.id,
                '<div class="form-group" ><label class="custom-control custom-checkbox mb-0" >'+
                '<input type="checkbox" style="margin: 0 auto" class="custom-control-input" name="outlets[]"  value="' + outlet.id + '"> '+
                '<span class="custom-control-label">'+outlet.name+'</span></label></div>', // Example for Outlets column
                outlet.user ? outlet.user.name + (outlet.user.trashed ? ' (Deleted User)' : '') : 'Deleted User',
                outlet.contact_person_name,
                outlet.phone
            ]).draw();

           // alert("we are here too");
        });
    }else{
        tableAllocate.draw();
        //alert("outlets empty");
    }

    }



    //______Basic Data Table
    var table2=$('#responsive-datatable').DataTable({
        "order": [0, 'desc'],
        'ordering': true,
        language: {
            searchPlaceholder: 'Search...',
            scrollX: "100%",
            sSearch: '',
        
        },
        buttons: [
            {
                extend: 'copy',
                title: 'RTMSExport',
                exportOptions: {
                    columns: function(idx, data, node) {
                        // Prevent export of columns with certain IDs
                        var columnId = $(node).attr('id');
                        return columnId !== 'map-column' && columnId !== 'view-column' &&columnId !== 'edit-column' && columnId !== 'delete-column';
                    },
                    format: {
                        body: function (data, row, column, node) {
                            return data.replace(/\n/g, '\r\n'); // Replace \n with \r\n for clipboard copy
                        }
                    }
                }
            }
            , 
        {
            extend: 'excelHtml5',
            title: 'RTMSExport',
            exportOptions: {
                columns: function(idx, data, node) {
                    // Prevent export of columns with certain IDs
                    var columnId = $(node).attr('id');
                    return columnId !== 'map-column' && columnId !== 'view-column' && columnId !== 'edit-column' && columnId !== 'delete-column';
                },
                format: {
                    body: function (data, row, column, node) {
                        return data.replace(/\n/g, '\r\n'); // Replace \n with \r\n for Excel
                    }
                }
            }
        }
        
        , 
        {
            extend: 'pdf',
            title: 'RTMSExport',
            exportOptions: {
                columns: function(idx, data, node) {
                    // Prevent export of columns with certain IDs
                    var columnId = $(node).attr('id');
                    return columnId !== 'map-column' && columnId !== 'view-column' &&columnId !== 'edit-column' && columnId !== 'delete-column';
                },
                format: {
                    body: function (data, row, column, node) {
                        return data.replace(/\n/g, '\n'); // Preserve newlines for PDF
                    }
                }
            },
            
        },
        
        ,         {
            extend: 'colvis',
            title: 'RTMSExport',
            // columns: function(idx, data, node) {
            //         // Prevent export of columns with certain IDs
            //         var columnId = $(node).attr('id');
            //         return columnId !== 'edit-column' && columnId !== 'delete-column';
            //     }
            
        }
    ],
        
    });



        // Custom filter function for date range comparison
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {

                // Check if the current table is table2
                    if (settings.nTable.id !== 'responsive-datatable') {
                        return true; // Skip filtering if not table2
                    }
                // var tableLoaded=$('#tableLoaded').val();

                // Alert the name of the 5th column (index 4)
                // var fifthColumnName = settings.aoColumns[4].sTitle;
                // alert("The name of the 5th column is: " + fifthColumnName);

                var dataColumn = settings.aoColumns.map(column => column.sTitle).indexOf("Date");
                 // alert(dataColumn);


                // if(tableLoaded=="visits"){
                //     dataColumn = 3; 
                // }else if(tableLoaded=="feedback"){
                //     dataColumn = 4;
                // }
                if(dataColumn!=-1){
                    


                const minDate = $('#dateFromInput').val();
                const maxDate = $('#dateToInput').val();
                const dateStr = data[dataColumn]; // Assuming the date is in the 4th column (index 3)

                // alert(minDate+"///"+maxDate);
    
                const parseDate = str => {
                    const [day, month, year] = str.split('-');
                    const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                    return new Date(year, months.indexOf(month), day);
                };

                function toCustomFormat(dateStr) {
                    const [year, month, day] = dateStr.split('-');
                    const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                    return `${day}-${months[parseInt(month) - 1]}-${year}`;
                }
    
                const minDateParsed = minDate ? parseDate(toCustomFormat(minDate)) : null;
                const maxDateParsed = maxDate ? parseDate(toCustomFormat(maxDate)) : null;
                const dateParsed = parseDate(dateStr);

                // alert(minDateParsed+"/////"+dateParsed);
    
                // Check if the date is within the range
                const withinMin = minDateParsed ? dateParsed >= minDateParsed : true;
                const withinMax = maxDateParsed ? dateParsed <= maxDateParsed : true;
    
                return withinMin && withinMax;

            }else{
                console.log("Date Column not found");
                return true; // Skip filtering if not the specific table
            }

                
            }
        );


        
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                if (settings.nTable.id !== 'responsive-datatable') {
                    return true; // Skip filtering if not the specific table
                }
     
        
                var timeColumnIndex = settings.aoColumns.map(column => column.sTitle).indexOf("Time");

                if(timeColumnIndex!=-1){
                    // alert("we get here");
        
                const minTime = $('#timeFromInput').val();
                const maxTime = $('#timeToInput').val();
                const timeStr = data[timeColumnIndex]; // Time column value
        
                const parseTime = str => {
                    const [hour, minute] = str.split(':');
                    return new Date(0, 0, 0, hour, minute);
                };
        
                const minTimeParsed = minTime ? parseTime(minTime) : null;
                const maxTimeParsed = maxTime ? parseTime(maxTime) : null;
                const timeParsed = parseTime(timeStr);

        
                const withinMin = minTimeParsed ? timeParsed >= minTimeParsed : true;
                const withinMax = maxTimeParsed ? timeParsed <= maxTimeParsed : true;
        
                return withinMin && withinMax;
            }else{
                console.log("Time column not found");
                return true; // Skip filtering if not the specific table
            }
            }
        );


        

            // Event handlers to trigger the table redraw when the date inputs change
    $('#dateFromInput, #dateToInput').on('change', function() {
        table2.draw(); // Trigger the custom filter when either date input changes
    });
    $('#timeFromInput, #timeToInput').change(function() {
        table2.draw();
    });


    table2.buttons().container()
        .appendTo('#responsive-datatable_wrapper .col-md-6:eq(0)');

//OUTLETS ALLOCATE TO USERS TABLE FORM
 
        // Handle 'select all' checkbox
        $('#selectalloutlets').on('click', function() {
            alert('clicked');
            var rows = tableAllocate.rows({ 'search': 'applied' }).nodes();
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });

        // Handle 'select all' checkbox
        $('#selectalloutletsroutes').on('click', function() {
            alert('clicked');
            var rows = tableAllocate.rows({ 'search': 'applied' }).nodes();
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });
    
        // Handle form submission
        $('#Outletstouser').on('submit', function(event) {

            $('#Outletstouser input[type="hidden"][name="outletcheckboxes[]"]').remove();

            event.preventDefault(); // Prevent the default form submission; 
            
    
            // Gather all checkboxes (including those not in view)
            var allCheckboxes = tableAllocate.$('input[type="checkbox"]');
    
            // Iterate through all checkboxes to collect their values
            var selectedCheckboxes = [];
            allCheckboxes.each(function() {
                if (this.checked) {
                    selectedCheckboxes.push($(this).val());
                }
            });
    
            // Log the selected checkboxes or send them to the server
            // console.log("Hello");
            // console.log(selectedCheckboxes);
            // console.log("Hello");
            
    
            // Optionally, you can add the selected checkboxes to the form as hidden inputs
            selectedCheckboxes.forEach(function(value) {
                $('<input>').attr({
                    type: 'hidden',
                    name: 'outletcheckboxes[]',
                    value: value
                }).appendTo('#Outletstouser');
                // alert(value);
            });

            // alert("stop");
    
            // Submit the form
            
            this.submit();
            
            
        });

    //OUTLETS ALLOCATE TO USERS TABLE FORM

    //OUTLETS ALLOCATE TO USERS TABLE FORM

//        Handle 'select all' checkbox
        $('#selectallusers').on('click', function() {
            var rows = tableAllocate.rows({ 'search': 'applied' }).nodes();
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });
    
        // Handle form submission
        $('#Userstotargetgroup').on('submit', function(event) {

            $('#Userstotargetgroup input[type="hidden"][name="usercheckboxes[]"]').remove();

            event.preventDefault(); // Prevent the default form submission;
            
    
            // Gather all checkboxes (including those not in view)
            var allCheckboxes = tableAllocate.$('input[type="checkbox"]');
    
            // Iterate through all checkboxes to collect their values
            var selectedCheckboxes = [];
            allCheckboxes.each(function() {
                if (this.checked) {
                    selectedCheckboxes.push($(this).val());
                }
            });
    
            // Log the selected checkboxes or send them to the server
            console.log(selectedCheckboxes);
    
            // Optionally, you can add the selected checkboxes to the form as hidden inputs
            selectedCheckboxes.forEach(function(value) {
                $('<input>').attr({
                    type: 'hidden',
                    name: 'usercheckboxes[]',
                    value: value
                }).appendTo('#Userstotargetgroup');
            });
    
            // Submit the form
            
            this.submit();
            
            
        });

    //OUTLETS ALLOCATE TO USERS TABLE FORM


    $('#Outletstorouteplan').on('submit', function(event) {

        $('#Outletstorouteplan input[type="hidden"][name="outletcheckboxes[]"]').remove();

        event.preventDefault(); // Prevent the default form submission; 
        

        // Gather all checkboxes (including those not in view)
        var allCheckboxes = tableAllocate.$('input[type="checkbox"]');

        // Iterate through all checkboxes to collect their values
        var selectedCheckboxes = [];
        allCheckboxes.each(function() {
            if (this.checked) {
                selectedCheckboxes.push($(this).val());
            }
        });

        // Log the selected checkboxes or send them to the server
        // console.log("Hello");
        // console.log(selectedCheckboxes);
        // console.log("Hello");
        

        // Optionally, you can add the selected checkboxes to the form as hidden inputs
        selectedCheckboxes.forEach(function(value) {
            $('<input>').attr({
                type: 'hidden',
                name: 'outletcheckboxes[]',
                value: value
            }).appendTo('#Outletstorouteplan');
            //alert(value);
        });

        //alert("stop");

        // Submit the form
        
        this.submit();
        
        
    });


    

    



    // $('#responsive-datatable tbody').on('click', 'tr', function () {
    //     var data = table2.row(this).data();
    //     alert('You clicked on ' + data[0] + '\'s row');
    //     table2.column(1).search(data[0]).draw();
    // });
    //table.column(0).click(alert("hello"));
    // <input type="hidden" id="tableLoaded" value="users"/>
    var tableLoaded=$('#tableLoaded').val();
    //alert(tableLoaded);
    if(tableLoaded=="users"){

        var nameColumnIndex = table2.columns().header().toArray().findIndex(header => $(header).text() === 'Name');
        var phoneColumnIndex = table2.columns().header().toArray().findIndex(header => $(header).text() === 'Phone');
        var emailColumnIndex = table2.columns().header().toArray().findIndex(header => $(header).text() === 'Email');

    $('#nameInput').on('keyup', function() {
        //alert(this.value);
        table2.column(nameColumnIndex).search(this.value).draw(); // Column index 0 for 'Name'
    });
    // alert("file loads");

    $('#phoneInput').on('keyup', function() {
        //alert(this.value);
        table2.column(phoneColumnIndex).search(this.value).draw(); // Column index 1 for 'Position'
    });

    $('#emailInput').on('keyup', function() {
        //alert(this.value);
        table2.column(emailColumnIndex).search(this.value).draw(); // Column index 0 for 'Name'
    });

  
    $('#targetsInput').change(function(){

        var selectedValue = $(this).val();
        table2.column(nameColumnIndex).search(this.value).draw(); 
        //console.log("Selected value: " + selectedValue);
      });
    }else if(tableLoaded=="targetgroups"){

        var nameColumnIndex = table2.columns().header().toArray().findIndex(header => $(header).text() === 'Name');
        var amountColumnIndex = table2.columns().header().toArray().findIndex(header => $(header).text() === 'Amount');

        $('#nameInput').on('keyup', function() {
            //alert(this.value);
            table2.column(nameColumnIndex).search(this.value).draw(); // Column index 0 for 'Name'
        });
        $('#amountInput').on('keyup', function() {
            //alert(this.value);
            table2.column(amountColumnIndex).search(this.value).draw(); // Column index 1 for 'Position'
        });


    }else if(tableLoaded=="clockins"){
        $('#nameInput').on('keyup', function() {
            // alert(this.value);
            var nameColumnIndex = table2.columns().header().toArray().findIndex(header => $(header).text() === 'Name');
            // alert(nameColumnIndex);
            table2.column(nameColumnIndex).search(this.value).draw(); // Column index 0 for 'Name'
        });
        // $('#dateInput').on('keyup', function() {
        //     //alert(this.value);
        //     table2.column(2).search(this.value).draw(); // Column index 1 for 'Position'
        // });
        // $('#timeInput').on('keyup', function() {
        // table2.column(2).search('>' + this.value, true, false).draw();
        // });

    }
    else if(tableLoaded=="clockouts"){
        $('#nameInput').on('keyup', function() {
            //alert(this.value);
            var nameColumnIndex = table2.columns().header().toArray().findIndex(header => $(header).text() === 'Name');
            table2.column(nameColumnIndex).search(this.value).draw(); // Column index 0 for 'Name'
        });
        // $('#dateInput').on('keyup', function() {
        //     //alert(this.value);
        //     table2.column(2).search(this.value).draw(); // Column index 1 for 'Position'
        // });
        // $('#timeInput').on('keyup', function() {
        // table2.column(2).search('>' + this.value, true, false).draw();
        // });

    }
    else if(tableLoaded=="visits"){
        
        console.log("table allocate");
        console.log(table2.columns().header().toArray());
        console.log("table allocate");

        var sellerNameColumnIndex = table2.columns().header().toArray().findIndex(header => $(header).text() === 'Seller Name');
        var outletNameColumnIndex = table2.columns().header().toArray().findIndex(header => $(header).text() === 'Outlet Name');




        $('#nameInput').on('keyup', function() {
            //alert(this.value);
            table2.column(sellerNameColumnIndex).search(this.value).draw(); // Column index 0 for 'Name'
        });
        $('#outletNameInput').on('keyup', function() {
            //alert(this.value);
            table2.column(outletNameColumnIndex).search(this.value).draw(); // Column index 0 for 'Name'
        });

        // $('#dateInput').on('change', function() {
        //     //alert(this.value);
        //     const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        //     // Function to convert YYYY-MM-DD to DD-MMM-YYYY
        //     function toCustomFormat(dateStr) {
        //         const [year, month, day] = dateStr.split('-');
        //         return `${day}-${months[parseInt(month) - 1]}-${year}`;
        //     }
            
        //     table2.column(3).search(toCustomFormat(this.value)).draw(); // Column index 1 for 'Position'
            
        // });

        // $('#dateInput').on('change', function() {
        //     //alert(this.value);
        //     const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        //     // Function to convert YYYY-MM-DD to DD-MMM-YYYY
        //     function toCustomFormat(dateStr) {
        //         const [year, month, day] = dateStr.split('-');
        //         return `${day}-${months[parseInt(month) - 1]}-${year}`;
        //     }
            
        //     table2.column(3).search(toCustomFormat(this.value)).draw(); // Column index 1 for 'Position'
            
        // });

        // $('#dateToInput').on('change', function() {
        //     //alert(this.value);
        //     const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        //     // Function to convert YYYY-MM-DD to DD-MMM-YYYY
        //     function toCustomFormat(dateStr) {
        //         const [year, month, day] = dateStr.split('-');
        //         return `${day}-${months[parseInt(month) - 1]}-${year}`;
        //     }
            
        //     table2.column(3).search(toCustomFormat(this.value)).draw(); // Column index 1 for 'Position'
            
        // });

        // $('#timeInput').on('keyup', function() {
        // table2.column(2).search('>' + this.value, true, false).draw();
        // });

    }
    else if(tableLoaded=="sales" || tableLoaded=="posaudits" || tableLoaded=="competitoranalysis" || tableLoaded=="feedback"){
    
        var nameColumnIndex = table2.columns().header().toArray().findIndex(header => $(header).text() === 'Seller Name');
        var productNameColumnIndex = table2.columns().header().toArray().findIndex(header => $(header).text().toLowerCase().includes('product'));
        var outletNameColumnIndex = table2.columns().header().toArray().findIndex(header => $(header).text() === 'Outlet Name');
        var teamNameColumnIndex = table2.columns().header().toArray().findIndex(header => $(header).text() === 'Team');

        // alert(nameColumnIndex+" "+outletNameColumnIndex)
        // alert(teamNameColumnIndex);
  

        $('#nameInput').on('keyup', function() {
            // alert(this.value);
            table2.column(nameColumnIndex).search(this.value).draw(); // Column index 0 for 'Name'
        });
        $('#outletNameInput').on('keyup', function() {
            // alert(thzzis.value);
            table2.column(outletNameColumnIndex).search(this.value).draw(); // Column index 0 for 'Name'
        });
        $('#productNameInput').on('keyup', function() {
            // alert(thzzis.value);
            table2.column(productNameColumnIndex).search(this.value).draw(); // Column index 0 for 'Name'
        });
        $('#teamNameInput').on('keyup', function() {
            // alert(thzzis.value);
            table2.column(teamNameColumnIndex).search(this.value).draw(); // Column index 0 for 'Name'
        });

    }
    else if(tableLoaded=="outlets"){
        
        var nameColumnIndex = table2.columns().header().toArray().findIndex(header => $(header).text() === 'Name');
        var sellerNameColumnIndex = table2.columns().header().toArray().findIndex(header => $(header).text() === 'Seller Name');
        var contactNameColumnIndex = table2.columns().header().toArray().findIndex(header => $(header).text() === 'Contact Name');


        $('#nameInput').on('keyup', function() {

            table2.column(nameColumnIndex).search(this.value).draw(); // Column index 1 for 'Name'
            
        });

        $('#sellerNameInput').on('keyup', function() {

            table2.column(sellerNameColumnIndex).search(this.value).draw(); // Column index 1 for 'Name'
            
        });

        $('#contactNameInput').on('keyup', function() {

            table2.column(contactNameColumnIndex).search(this.value).draw(); // Column index 2 for 'Contact Name'
        });
        // $('#phoneInput').on('keyup', function() {
        //     //alert(this.value);
        //     table2.column(3).search(this.value).draw(); // Column index 3 for 'Phone'
        // });

    }
    else if(tableLoaded=="locations" || tableLoaded=="trips"){
        
        var driverNameColumnIndex = table2.columns().header().toArray().findIndex(header => $(header).text() === 'Driver');


        $('#driverNameInput').on('keyup', function() {

            table2.column(driverNameColumnIndex).search(this.value).draw(); // Column index 1 for 'Name'
            
        });


        // $('#phoneInput').on('keyup', function() {
        //     //alert(this.value);
        //     table2.column(3).search(this.value).draw(); // Column index 3 for 'Phone'
        // });

    }

    else if(tableLoaded=="Outletstouser"){
        console.log("table allocate");
        console.log(tableAllocate.columns().header().toArray());
        console.log("table allocate");

        // var firstColumnHeader = tableAllocate.columns().header().toArray()[1];
        // var firstColumnName = $(firstColumnHeader).text();
        // alert(firstColumnName);

        var outletNameColumnIndex = tableAllocate.columns().header().toArray().findIndex(header => $(header).text() === "Outlet Name");
        var sellerColumnIndex = tableAllocate.columns().header().toArray().findIndex(header => $(header).text() === 'Seller');
        var contactNameColumnIndex = tableAllocate.columns().header().toArray().findIndex(header => $(header).text() === 'Contact Name');
        var contactPhoneColumnIndex = tableAllocate.columns().header().toArray().findIndex(header => $(header).text() === 'Contact Phone');
        
  
        
        $('#nameInput').on('keyup', function() {
            // alert(this.value);
            // alert(outletNameColumnIndex);
            tableAllocate.column(outletNameColumnIndex).search(this.value).draw(); // Column index 1 for 'Name'
        });
        $('#currentSellerInput').on('keyup', function() {
            // alert(this.value);
            tableAllocate.column(sellerColumnIndex ).search(this.value).draw(); // Column index 1 for 'Name'
        });
        $('#contactNameInput').on('keyup', function() {
            // alert(this.value);
            tableAllocate.column(contactNameColumnIndex).search(this.value).draw(); // Column index 2 for 'Contact Name'
        });
        $('#phoneInput').on('keyup', function() {
            // alert(this.value);
            tableAllocate.column(contactPhoneColumnIndex).search(this.value).draw(); // Column index 3 for 'Phone'
        });

    }
    else if(tableLoaded=="products"){

        var nameColumnIndex = table2.columns().header().toArray().findIndex(header => $(header).text() === "Product Name");
        var skuColumnIndex = table2.columns().header().toArray().findIndex(header => $(header).text() === "SKU");
 
        $('#nameInput').on('keyup', function() {
            //alert(this.value);
            table2.column(nameColumnIndex).search(this.value).draw(); // Column index 1 for 'Name'
        });
        $('#skuInput').on('keyup', function() {
            //alert(this.value);
            table2.column(skuColumnIndex).search(this.value).draw(); // Column index 2 for 'Contact Name'
        });
        // $('#priceInput').on('keyup', function() {
        //     //alert(this.value);
        //     table2.column(3).search(this.value).draw(); // Column index 3 for 'Phone'
        // });

    }else if(tableLoaded=="regions"){
        var nameColumnIndex = table2.columns().header().toArray().findIndex(header => $(header).text() === "Region Name");
        $('#nameInput').on('keyup', function() {
            //alert(this.value);
            table2.column(nameColumnIndex).search(this.value).draw(); // Column index 1 for 'Name'
        });

    }else if(tableLoaded=="territories"){
        $('#nameInput').on('keyup', function() {
            var nameColumnIndex = table2.columns().header().toArray().findIndex(header => $(header).text() === "Territory Name");
            //alert(this.value);
            table2.column(nameColumnIndex).search(this.value).draw(); // Column index 1 for 'Name'
        });
        $('#regionInput').on('keyup', function() {
            var nameColumnIndex = table2.columns().header().toArray().findIndex(header => $(header).text() === "Region");
            //alert(this.value);
            table2.column(nameColumnIndex).search(this.value).draw(); // Column index 1 for 'Name'
        });

    }else if(tableLoaded=="default"){
        // alert('we are here');
        
        var nameColumnIndex = table2.columns().header().toArray().findIndex(header => $(header).text() === 'Name');

        if(nameColumnIndex>0){
        $('#nameInput').on('keyup', function() {
            table2.column(nameColumnIndex).search(this.value).draw(); // Column index 1 for 'Name'
        });
        
        }
        }


    //______File-Export Data Table
    var table = $('#file-datatable').DataTable({
        buttons: ['copy', 'excel', 'pdf', 'colvis'],
        language: {
            searchPlaceholder: 'Search...',
            scrollX: "100%",
            sSearch: '',
        }
    });
    table.buttons().container()
        .appendTo('#file-datatable_wrapper .col-md-6:eq(0)');

    //______Delete Data Table
    var table = $('#delete-datatable').DataTable({
        language: {
            searchPlaceholder: 'Search...',
            sSearch: '',
        }
    });
    $('#delete-datatable tbody').on('click', 'tr', function() {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        } else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });
    $('#button').on('click', function() {
        table.row('.selected').remove().draw(false);
    });
    $('#example3').DataTable( {
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal( {
                    header: function ( row ) {
                        var data = row.data();
                        return 'Details for '+data[0]+' '+data[1];
                    }
                } ),
                renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
                    tableClass: 'table'
                } )
            }
        }
    } );
    $('#example2').DataTable({
		responsive: true,
        "order": [0, 'desc'],
        'ordering': true,
		language: {
			searchPlaceholder: 'Search...',
			sSearch: '',
			lengthMenu: '_MENU_ items/page',
		}
	});
	

    //______Select2 
    $('.select2').select2({
        minimumResultsForSearch: Infinity
    });

});