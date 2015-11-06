var pagination = {

    property : {
        url : null,
        wrapper         : ".wrap-table",
        button          : ".wrap-table .pagination a",
        searchField     : "#searchField",
        filterField     : "#filterField",
        showNumberField : "#showNumberField",        
        //showNumberField1 : "#showNumberField1",
        searchForm      : "#filter-form",
        buttonFilter    : ".btn-filter-sm",
        keyword         : '#keyword',
        oldKeyword      : null,
        filterBy        : 0,
        oldFilterBy     : 0,
        showNumber      : 10,
        oldShowNumber   : 10,
        isReset         : 0
    },
    init  :   function( obj ){
        $.extend(this.property, obj);
        this.property.wrapper           = $(this.property.wrapper);
        this.property.searchField       = $(this.property.searchField);
        this.property.filterField       = $(this.property.filterField);
        this.property.showNumberField   = $(this.property.showNumberField);
        this.property.buttonFilter      = $(this.property.buttonFilter);
        this.property.searchForm        = $(this.property.searchForm);
        this.property.keyword           = $(this.property.keyword);

        this.addEvent();
        this.getResult();
    },
    addEvent: function(){
        var that = this;
        this.property.searchField.change(function(){
            that.search($(this).val());
        });
        this.property.filterField.change(function(){
            that.filter($(this).val());
        });
        this.property.showNumberField.change(function(){
            that.changeShow($(this).val());
        });
        this.property.buttonFilter.click(function(e) {
            e.preventDefault();
            that.filterSubmit();
        });
        /*this.property.showNumberField1.change(function(){
            that.changeShow($(this).val());
        })*/
    },
    changeShow : function(number){
        this.property.showNumber = number;
        this.getResult();
    },
    sort : function(defaultField, defaultOrder){
        this.property.defaultField = defaultField;
        this.property.defaultOrder = defaultOrder;
        this.getResult();
    },
    search : function(keyword){
        this.property.keyword = keyword;
        this.getResult();
    },
    filter : function(filterBy){
        this.property.filterBy = filterBy;
        this.getResult();
    },
    filterSubmit : function(url){
        showLoading();
        this.getIsReset();
        var urlRequest = typeof(url) != 'undefined' ? url : this.property.url;
        var that = this;
        var keyword = that.property.keyword.length ? that.property.keyword.val() : '';
        $.get(
            urlRequest,{
                isReset         : that.property.isReset,
                defaultField    : that.property.defaultField,
                defaultOrder    : that.property.defaultOrder,
                filterBy        : that.property.searchForm.serializeArray(),
                keyword         : keyword,
                showNumber      : that.property.showNumber
            },
            function(data){
                that.property.wrapper.html(data);
                that.property.oldKeyword = that.property.keyword;
                that.property.oldFilterBy = that.property.filterBy;
                that.property.oldShowNumber = that.property.showNumber;
                that.afterResult();
               
            }
        )
    },
    afterResult : function(){
        var button = $(this.property.button);
        var that = this;
        button.click(function(e){
            e.preventDefault();
            href = $(this).attr("href");
            // that.property.url = href;
            that.filterSubmit(href);
        });
        
        $('.showNumberField').change(function(e){                    
            that.changeShow($(this).val());
        });
        hideLoading();
    },
    getResult :   function (){
        showLoading();
        this.getIsReset();

        var that = this;
        $.get(
            this.property.url,{
                isReset         : that.property.isReset,
                defaultField    : that.property.defaultField,
                defaultOrder    : that.property.defaultOrder,
                // keyword         : that.property.keyword,
                filterBy        : that.property.filterBy,
                showNumber      : that.property.showNumber
            },
            function(data){
                that.property.wrapper.html(data);
                that.property.oldKeyword = that.property.keyword;
                that.property.oldFilterBy = that.property.filterBy;
                that.property.oldShowNumber = that.property.showNumber;
                that.afterResult();
            }
        )
    },
    getIsReset : function(){

        var property = this.property;
        if( (property.oldKeyword != property.keyword) || (property.oldFilterBy != property.filterBy) || (property.oldShowNumber != property.showNumber) ){
            this.property.isReset = 1;
        }else{
            this.property.isReset = 0;
        }
    }

};

function toggleBoolean(id, value, field){
    var url = root+module+"/toggle-boolean";
    $.post(
        url,
        {
            id : id,
            field : field,
            value : value,
            token: $('#filter-form input[name=token]').val()
        },
        function(data){

            if( data == "access-denied" ){
                alert("Bạn không có quyền thực thi hành động này");
            }else if( data != "fail" ){
                $("."+field+"-"+id).html( data );
            }
        }
    );
}


function deleteItem(id) {
    if (confirm("Bạn có muốn xóa?")) {
        var url = root+module+"/delete";
        $.post(
            url,
            {
                id : id,
                _token: $('#filter-form input[name=token]').val()
            },
            function(data){
                if( data == "success" ){
                    pagination.getResult();
                }else if( data == "access-denied" ){
                    alert("Bạn không có quyền thực thi hành động này");
                }
            }
        );
    }
    return false;
}

function showLoading(){
    $("#modalLoading").modal("show");
}

function hideLoading(){
    $("#modalLoading").modal("hide");
}



