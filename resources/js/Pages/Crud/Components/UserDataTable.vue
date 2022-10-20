<template>
    <table class="table-fixed w-full mt-10">
        <thead>
        <tr class="border-2">
            <th class="border-2">Image</th>
            <th class="border-2">Name</th>
            <th class="border-2">Email</th>
            <th class="border-2">Gender</th>
            <th class="border-2">Skills</th>
            <th class="border-2">Action</th>
        </tr>
        </thead>
        <tbody>
        <tr class="border-2" v-for="(user, uIdx) in userList" :key="'user'+uIdx">
            <td class="text-center p-10"><img :src="user.userDetail?.path" alt=""></td>
            <td class="text-center border-2">{{ user.name }}</td>
            <td class="text-center border-2">{{ user.email }}</td>
            <td class="text-center border-2">{{ user.userDetail?.genderLabel }}</td>
            <td class="text-center w-full flex flex-wrap gap-2">
                <span class="bg-green-500 text-white px-2 rounded my-1" v-for="(skill, sIdx) in user.skills" :key="'skill'+sIdx">{{ skill.name }}</span>
            </td>

            <td class="text-center border-2">
                <span class="bg-blue-500 text-white px-2 rounded ml-1 cursor-pointer" @click.prevent="setEdit(user)">Edit</span>
                <span class="bg-red-500 text-white px-2 rounded ml-1 cursor-pointer" @click.prevent="setDelete(user)">Delete</span>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
import {mapActions, mapGetters} from "vuex";

export default {
    name: "UserDataTable",

    data(){
        return {
            userList: []
        }
    },
    mounted() {
        this.getUsers();
    },

    methods: {
        ...mapActions([
            'fetchUsers',
            'deleteUser'
        ]),

        async getUsers(){
            await this.fetchUsers().then((response) => {
                if (response.status === 200){
                    this.userList = this.users;
                }
            });
        },

        setEdit(user){
            this.$store.commit('setUserInfo', user)
        },

        setDelete(user){
            this.$swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.deleteUser(user.id)
                        .then((response) => {
                            if (response.status === 200){
                                this.$swal({
                                    position: 'center',
                                    icon: 'success',
                                    title: response.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                            } else {
                                this.$swal({
                                    position: 'center',
                                    icon: 'error',
                                    title: response.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                            }
                        })
                }
            })
        }
    },

    computed: {
        ...mapGetters([
            'users',
        ])
    }
}
</script>

<style scoped>

</style>
