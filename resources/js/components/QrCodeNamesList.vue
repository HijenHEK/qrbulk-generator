<template>
    <div class="p-2 mx-2 d-flex flex-center justify-center">
        <textarea name="qr_code_names_text" id="qr_code_names_text" v-model="qr_code_names_text" @change="createQrCodeNameList()" cols="30" rows="10"></textarea>
        <br>
        <ul v-if="qr_code_names_list.length" class="d-flex flex-column unstyled">
            <li v-for=" (qr_code_name, index) in qr_code_names_list" :key="index">{{ qr_code_name }}</li>
        </ul>
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
                qr_code_names_list : []
            }
        },
        methods : {
            createQrCodeNameList() {
                this.qr_code_names_list = this.qr_code_names_text.split("\n")
                this.qr_code_names_list = this.qr_code_names_list.filter((e)=>{
                    e = e.trim();
                    return e.length ;
                });
                axios.post('/qr_code_bulk_generate' , {
                    'qr_code_names_list' : this.qr_code_names_list
                }).then(()=>{
                    console.log('success');
                })
            }
        }
    }
</script>
