<template>
  <div>
    <div v-if="err" class="my-1">
      <button class="alert alert-danger">{{ err }}</button>
    </div>

    <textarea
      class="form-control"
      placeholder="values goes here !"
      name="qr_code_names_text"
      id="qr_code_names_text"
      v-model="qr_code_names_text"
      @keypress="handleInputChange"
      cols="30"
      rows="10"
    ></textarea>
    <br />
    <div>
      <div class="form-group">
        <label for="file-input">Or upload a <strong>.txt</strong> file</label>
        <br />
        <input
          type="file"
          name="input_file"
          ref="input_file"
          accept="plain/text"
          @input="upload"
          class=""
        />
      </div>
    </div>

    <div v-if="loading">
      <button class="btn btn-outline-dark btn-disabled" disabled>
        Loading ...
      </button>
    </div>
    
    <div v-else-if="folder" >
      <button class="btn btn-success" @click="downloadFile">
        Download Results
      </button>

      <button class="btn btn-warning" @click="cleanGenerator">
        Reset
      </button>
    </div>

    <div v-else-if="qr_code_names_list">
      <button class="btn btn-primary" @click="createQrCodeNameList">
        Generate QrCodes
      </button>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    console.log("Component mounted.");
  },
  data() {
    return {
      qr_code_names_text: "",
      qr_code_names_list: [],
      output_file: "",
      loading: false,
      err: false,
      input_file: null,
      folder : null,
      output_file:null
    };
  },
  methods: {
    clearErr() {
      this.err = "";
    },
    clearAll(){
        if(this.input_file) {
          this.input_file=null;
          this.$refs.input_file.value = null;
        }
        this.output_file=null;
        this.err =null;
        if(this.folder) {
            this.cleanStorage().then(()=>{
              this.folder=null;
              this.clearErr()
            });
        }
    },

    handleInputChange: _.debounce(function(){this.clearAll()},200),

    createQrCodeNameList() {
      if(!this.qr_code_names_text) {
        this.err="Please enter at least one line !"
        return;
      }
      this.qr_code_names_list = this.qr_code_names_text.split("\n");
      this.qr_code_names_list = this.qr_code_names_list.filter((e) => {
        e = e.trim();
        return e.length;
      });
      this.loading = true;
      this.clearErr;
      axios.post("/qr_code_bulk_generate", {
          qr_code_names_list: this.qr_code_names_list,
        })
        .then((res) => {
          this.clearErr;
          this.folder = res.data.folder;
          if (!this.folder) this.err = "Oops something went wrong please try again !";
          this.loading = false;
        })
        .catch((err) => {
          this.loading = false;
          this.err = "Oops something went wrong please try again !";
        });
    },
    async upload(event) {
      this.qr_code_names_text = '';
      this.qr_code_names_list = [];
      let files =  await this.$refs.input_file.files 
      if( files.length == 0 ) {
        return;
      }
      this.input_file = await files[0];
      if (
        this.input_file && (
        this.input_file.type != "text/plain" ||
        this.input_file.name.split(".")[
          this.input_file.name.split(".").length - 1
        ] != "txt"
      )) {
        this.err = "input file should be a plain .txt text file";
        return;
      }
      this.clearErr;
      var reader = new FileReader();
      reader.readAsText(this.input_file);   
      reader.onload = (evt) => {
        this.qr_code_names_text = evt.target.result;
        this.createQrCodeNameList;
      };
    },
    cleanStorage() {
      return axios.post('/clean_storage/' + this.folder);
    },
    cleanGenerator(){
      this.loading = true;
      this.cleanStorage()
        .then((res) => {
          this.clearAll();
          this.qr_code_names_text = null ;
          this.qr_code_names_list = [];
                    this.loading = false;

        })
        .catch((err) => {
          this.loading = false;
          this.err = err;
        });
     
    },
    downloadFile() {
      this.loading = true;

      axios
        .post("/generate_zip_file/" + this.folder)
        .then((res) => {
          this.clearErr;
          this.output_file = res.data.zip;
          window.open("/downloads/" + this.output_file, "_blank");
                this.output_file = "";
                  this.cleanGenerator;
                  this.loading = false;
          })
        .catch((err) => {
          this.loading = false;
          this.cleanGenerator;
          this.err = err;
        });
   

    },
  },
};
</script>


