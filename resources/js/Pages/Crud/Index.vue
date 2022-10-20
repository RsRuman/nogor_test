<template>
    <Head title="CRUD"/>
    <div class="max-w-sm mx-auto w900">
        <form class="w-full bg-gray-200 p-2">
            <!--      Name      -->
            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/5">
                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="name">
                        Name
                    </label>
                </div>
                <div class="md:w-2/3">
                    <input
                        v-model="form.name"
                        class="bg-white appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                        id="name" type="text">
                    <div class="p-2 text-red-500" v-if="validationMessages.hasOwnProperty('name')">{{ validationMessages.name[0] }}</div>
                </div>
            </div>

            <!--      Email      -->
            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/5">
                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="email">
                        Email
                    </label>
                </div>
                <div class="md:w-2/3">
                    <input
                        v-model="form.email"
                        class="bg-white appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                        id="email" type="email">
                    <div class="p-2 text-red-500" v-if="validationMessages.hasOwnProperty('email')">{{ validationMessages.email[0] }}</div>
                </div>
            </div>

            <!--      Image      -->
            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/5">
                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="image">
                        Image
                    </label>
                </div>
                <div class="md:w-2/3">
                    <input
                        @change="uploadImage($event)"
                        ref="inputFile"
                        class="bg-white appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                        id="image" type="file">
                    <div class="p-2 text-red-500" v-if="validationMessages.hasOwnProperty('image')">{{ validationMessages.image[0] }}</div>
                </div>
            </div>

            <!--      Gender      -->
            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/5">
                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="gender">
                        Gender
                    </label>
                </div>
                <div class="">
                    <label for="male">Male</label>
                    <input type="radio" class="mx-2" value="1" id="male" v-model="form.gender">
                </div>
                <div class="">
                    <label for="female">Female</label>
                    <input type="radio" class="mx-2" id="female" value="2" v-model="form.gender">
                </div>
                <div class="p-2 text-red-500" v-if="validationMessages.hasOwnProperty('gender')">{{ validationMessages.gender[0] }}</div>
            </div>

            <!--      Skills      -->
            <div class="md:flex mb-6">
                <div class="md:w-1/5 flex flex-col">
                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="skills">
                        Skills
                    </label>
                </div>

                <div class="grid grid-cols-2 gap-1">
                    <div class="col-span-1">
                        <input type="checkbox" class="mx-2" id="laravel" value="1" v-model="form.skills">
                        <label for="laravel">Laravel</label>
                    </div>

                    <div class="col-span-1">
                        <input type="checkbox" class="mx-2" id="codeIgniter" value="2" v-model="form.skills">
                        <label for="codeIgniter">CodeIgniter</label>
                    </div>

                    <div class="col-span-1">
                        <input type="checkbox" class="mx-2" id="ajax" value="3" v-model="form.skills">
                        <label for="ajax">Ajax</label>
                    </div>

                    <div class="col-span-1">
                        <input type="checkbox" class="mx-2" id="vuejs" value="4" v-model="form.skills">
                        <label for="vuejs">Vue.js</label>
                    </div>

                    <div class="col-span-1">
                        <input type="checkbox" class="mx-2" id="mysql" value="5" v-model="form.skills">
                        <label for="mysql">MySQL</label>
                    </div>

                    <div class="col-span-1">
                        <input type="checkbox" class="mx-2" id="api" value="6" v-model="form.skills">
                        <label for="api">API</label>
                    </div>
                </div>
            </div>

            <div class="md:flex mb-6">
                <div class="md:w-1/5 flex flex-col">
                </div>
                <div class="p-2 text-red-500 md:flex mb-6" v-if="validationMessages.hasOwnProperty('skills')">{{ validationMessages.skills[0] }}</div>
            </div>


            <div class="md:flex md:items-center">
                <div class="md:w-1/5"></div>
                <div class="md:w-1/5">
                    <button
                        @click.prevent="submit"
                        class="shadow bg-purple-500 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded"
                        type="button">
                        Submit
                    </button>
                </div>
            </div>
        </form>
    </div>

    <hr class="border-2">

    <div class="max-w-sm mx-auto w900">
        <user-data-table></user-data-table>
    </div>
</template>

<script>
import {Head, Link} from '@inertiajs/inertia-vue3';
import UserDataTable from "./Components/UserDataTable.vue";
import {mapActions, mapGetters} from "vuex";

export default {
    name: "Index",
    components: {
        Head,
        UserDataTable
    },

    data(){
        return {
            form: {
                id: '',
                name: '',
                email: '',
                image: '',
                gender: 1,
                skills: []
            },

            validationMessages: {}
        }
    },

    methods: {
        ...mapActions([
            'storeUser',
            'updateUser'
        ]),

        uploadImage(event){
            this.form.image = event.target.files[0];
        },

        async submit(){
            let data = new FormData();

            data.append('name', this.form.name);
            data.append('email', this.form.email);
            data.append('image', this.form.image);
            data.append('gender', this.form.gender);
            for (let i = 0; i < this.form.skills.length; i++) {
                data.append('skills[]', this.form.skills[i]);
            }

            if (this.form.id !== ''){
                data.append('_method', 'PUT');
                const myObject = {
                    'id' : this.form.id,
                    'fData' : data
                };
                await this.updateUser(myObject)
                    .then((response) => {
                        if (response.status === 200){
                            this.resetForm();
                            this.$store.commit('setValidationErrors', Object.assign({}, {}));
                            this.$swal({
                                position: 'center',
                                icon: 'success',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }

                        else if (response.status === 406){
                            this.$store.commit('setValidationErrors', response.errors);
                            this.$swal({
                                position: 'center',
                                icon: 'error',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                        else {
                            this.$swal({
                                position: 'center',
                                icon: 'warning',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                    })
            } else {
                await this.storeUser(data)
                    .then((response) => {
                        if (response.status === 200){
                            this.resetForm();
                            this.$store.commit('setValidationErrors', Object.assign({}, {}))
                            this.$swal({
                                position: 'center',
                                icon: 'success',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }

                        else if (response.status === 406){
                            this.$store.commit('setValidationErrors', response.errors);
                            this.$swal({
                                position: 'center',
                                icon: 'error',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                        else {
                            this.$swal({
                                position: 'center',
                                icon: 'warning',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                    })
            }

        },

        resetForm(){
            this.$refs.inputFile.value=null;
            this.form.name = '';
            this.form.email = '';
            this.form.image = '';
            this.form.gender = 1;
            this.form.skills = [];
        }
    },

    computed: {
        ...mapGetters([
            'userInfo',
            'validationErrors'
        ]),

        checkValidationErrors(){
            return JSON.parse(JSON.stringify(this.validationErrors));
        },

        checkUserInfo(){
            return JSON.parse(JSON.stringify(this.userInfo));
        }
    },

    watch: {
        checkValidationErrors: {
            handler: function handler(newVal, oldVal){
                if (newVal !== oldVal){
                    this.validationMessages = this.validationErrors;
                }
            },
            deep: true
        },

        checkUserInfo: {
            handler: function handler(newVal, oldVal){
                if (newVal !== oldVal){
                    this.form.id = this.userInfo.id;
                    this.form.name = this.userInfo.name;
                    this.form.email = this.userInfo.email;
                    this.form.gender = this.userInfo.userDetail.gender
                    this.form.skills = this.userInfo.skills.map((item) => item.id)
                }
            },
            deep: true
        }
    }

}
</script>

<style scoped>

.w900 {
    max-width: 900px;
    margin: 0 auto;
}

</style>
