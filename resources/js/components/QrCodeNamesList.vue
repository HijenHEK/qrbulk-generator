<template>
<div >
        
        <div v-if="err" class="my-1">
                <button class="alert alert-danger">{{ err }}</button>
        </div>
        <textarea class="form-control" placeholder="values goes here !" name="qr_code_names_text" id="qr_code_names_text" v-model="qr_code_names_text" cols="30" rows="10"></textarea>
        <br>
        

        <div v-if="loading">
                            <button class="btn btn-outline-dark btn-disabled" disabled>Loading ...</button>

        </div>
         <div v-else-if="file">
                <button class="btn btn-success" @click="downloadFile">Download Results</button>
        </div>
       

        <div v-else-if="qr_code_names_list" >
            <button class="btn btn-primary" @click="createQrCodeNameList()" > Generate QrCodes</button>
        </div>
        
   
</div>
    
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data () {
            return {
                qr_code_names_text : '',
                qr_code_names_list : [],
                file : '',
                loading : false ,
                err : false ,
            }
        },
        methods : {
            createQrCodeNameList() {
                
                this.qr_code_names_list = this.qr_code_names_text.split("\n")
                this.qr_code_names_list = this.qr_code_names_list.filter((e)=>{
                    e = e.trim();
                    return e.length ;
                });
                this.loading = true ;
                                    this.err = false;

                axios.post('/qr_code_bulk_generate' , {
                    'qr_code_names_list' : this.qr_code_names_list
                }).then((res)=>{
                    this.err = false;
                    this.file = res.data.zip;
                    if(!this.file) this.err = 'Oops something went wrong please try again !';
                    this.loading = false;
                }).catch((err)=>{
                    this.loading = false;
                    this.err = err;
                })
            },
            async downloadFile() {
                window.open( '/downloads/' + this.file , '_blank' )
                this.file = '';
                return ;
                //         axios.post('/downloads/' + this.file , {
                //                 headers:{
                //    'Content-Type': 'application/json; application/octet-stream'
                //     },
                //     responseType: 'arraybuffer'
                //                     }).then((response) => {
                //                         const blob = new Blob([response.data],{type:'application/zip'});
                //                         const link = document.createElement('a');
                //                         link.href = window.URL.createObjectURL(blob)
                //                         link.download = this.file;
                //                         document.body.appendChild(link);
                //                         link.click();
                //                     })
                //                     .catch(function (error) {
                //                         console.log(error);
                //                     });
                //                 }
            }
    }
    }
</script>


