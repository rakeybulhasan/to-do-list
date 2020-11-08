<?php
require_once __DIR__ . '/vendor/autoload.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Exam Assignment</title>

    <!-- Bootstrap core CSS -->
    <link href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" rel="stylesheet" crossorigin="anonymous">
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->

</head>

<body>

<!-- Page Content -->
<div class="container" style="padding-top: 50px">

</div>
<!-- /.container -->

<div id="app" style="margin-bottom: 20px">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <p style="font-size: 25px" class="text-center display-4 pt-2">Todos</p>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <table class="table">
                    <thead>
                    <tr class="text-center">
                        <th style="border: none" colspan="5"><input type="text" name="title" v-model="title" @keydown.enter="submitData" class="form-control" placeholder="Enter title"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-if="todos && todos.length>0" v-for="(todo, index) in todos" :key="todo.id">
                        <td><input :disabled="todo.status == 3" type="checkbox" v-model="selectedItems" @change="statusUpdate" :value="todo.id"></td>
                        <td colspan="4">
                            <a v-show="editOffset != index" href="javascript:void(0)" @click.stop="status!=3?startEditing(index):null" :style="status==3 ? { 'color': 'gray','text-decoration': 'line-through','pointer-events':'none' } : { 'color': 'black','text-decoration':'none' }" >
                                {{ todo.title }}
                            </a>
                            <input v-show="editOffset==index" v-on:blur="disableEdit" type="text" :id = "'item-user-'+index"
                                                               @keydown.enter="updatePost" class="form-control" v-model="editTodo.title"></td>
                    </tr>
                    <tr v-else>
                        <td colspan="5">No record found</td>
                    </tr>
                    </tbody>
                    <tfoot v-if="todos && todos.length>0">
                    <td>{{todos.length}} <span v-if="todos && todos.length<2">item</span> <span v-else>items</span></td>
                    <td><button type="button" class="btn btn-sm" :value="1" @click="allRecord">All</button> </td>
                    <td><button type="button" class="btn btn-sm" :value="2" @click="activeRecord">Active</button> </td>
                    <td><button type="button" class="btn btn-sm" :value="3" @click="completedRecord">Completed</button> </td>
                    <td><button v-if="todos && todos.length>0&&status==3" type="button" class="btn btn-sm" @click="deleteCompletedRecord">Clear Completed</button> </td>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap core JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.0/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.12/vue.js"></script>
<!--<script src="assets/jquery/jquery.min.js"></script>-->
<!--<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>-->
<!--<script src="assets/js/script.js"></script>-->
<!--<script src="assets/js/main.js"></script>-->
<script type="text/javascript">
    var app = new Vue({
        el:'#app',
        data:{
            errorMsg: "",
            editOffset: -1,
            successMsg: "",
            title:'',
            allData:'',
            todos:[],
            editTodo:{},
            editPostOri: {},
            selectedItems:[],
            status:1,
        },
        mounted: function(){
            this.getAllProducts();
        },
        methods:{
            getAllProducts(){
                var fd = new FormData();
                fd.append('status', this.status);
                axios.post('ajax-action.php?action=PRODUCT_LIST',fd).then(function (response) {
                    if(response.data.error){
                        app.errorMsg= response.data.message;
                    }else {
                        app.todos = response.data.todos;
                    }
                })
            },

            submitData: function(event) {
                    if(app.title!=''){
                        axios.post("ajax-action.php?action=INSERT", {
                            titleData:app.title
                        }).then(function (response) {
                            if(response.data.error){
                                app.errorMsg= response.data.message;
                            }else{
                                app.errorMsg='';
                                app.successMsg= response.data.message;
                                app.getAllProducts();
                                app.title='';
                            }
                        });
                    }else {
                        app.successMsg='';
                        app.errorMsg = 'Title field is required.'
                    }
            },
            selectSingleRecord:function(){

            },
            allRecord:function(e){
                this.status=e.target.value;
                app.getAllProducts();
            },
            activeRecord:function(e){
                this.status=e.target.value;
                app.getAllProducts();
            },
            completedRecord:function(e){
                this.status=e.target.value;
                app.getAllProducts();
            },
            statusUpdate:function(e){
                if(this.selectedItems && this.selectedItems.length>0){
                    var fd = new FormData();
                    fd.append('ids', this.selectedItems);
                    fd.append('status', parseFloat(this.status)+1);
                    axios.post("ajax-action.php?action=UPDATE_STATUS",fd).then(function (response) {
                        if(response.data.error){
                            app.errorMsg= response.data.message;
                        }else{
                            app.selectedItems=[];
                            app.successMsg= response.data.message;
                            app.getAllProducts();
                        }
                    })
                }else {
                    app.getAllProducts();
                }

            },
            startEditing:function(index) {
                this.editOffset = index;
                this.editTodo = this.todos[index];
                // set focus ke element input
                this.$nextTick(function(){
                    document.getElementById('item-user-'+this.editOffset).focus()
                }.bind(this))
            },
            updatePost:function() {
                this.editOffset = -1;
                axios.post("ajax-action.php?action=UPDATE", this.editTodo).then(function (response) {

                    if(response.data.error){
                        app.errorMsg= response.data.message;
                    }else{
                        app.editTodo={};
                        app.successMsg= response.data.message;
                        app.getAllProducts();
                    }
                })
            },
            deleteCompletedRecord:function() {
                axios.get("ajax-action.php?action=DELETE").then(function (response) {
                    if(response.data.error){
                        app.errorMsg= response.data.message;
                    }else{
                        app.status=1;
                        app.successMsg= response.data.message;
                        app.getAllProducts();
                    }
                })

            },
            disableEdit: function() {
                this.editOffset = -1;
            },
            clearMsg(){
                this.errorMsg = '';
                this.successMsg = '';
            }
        }
    });


</script>

</body>

</html>
