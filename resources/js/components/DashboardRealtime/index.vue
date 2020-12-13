<template>
    <div id="realtime" class="card card-body bg-orange">
        <div class="mb-0">
            <div class="heading3">ผู้ใช้ที่ใช้งานอยู่ขณะนี้</div>
        </div>

        <div class="counter">{{ liveUser }}</div>


        <div v-if="last_update" class="text-white-50">Last Update: {{ last_update }}</div>
        <div class="table-body-wrapper">
            <table>
                <tbody>
                    <tr v-for="(item, i) in items" :key="i" v-if="i < 5">
                        <td>{{ item.path }}</td>
                        <!-- <td class="td-date">{{ date_format(item.date) }}</td> -->
                        <td class="td-count">{{ item.count }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {

    data(){
        return {
            liveUser: 0,

            loading: false,

            items: [],
            last_update: null,

            // debug: true,
        }
    },
    mounted(){

        this.getData();
    },

    // watch: {
    //     items(){

    //         if( items.length ){

    //             let item = items.find((n,i)=>i==0);

    //             console.log(item);
    //         }
    //     }
    // },
    methods: {

        runTimer(){
            const vm = this;

            if (vm.debug) {
                console.log('runTimer...');
            }

            setTimeout(() => {

                vm.getData();
            }, 3000);

        },
        getData(){
            const vm = this;


            if (vm.debug) {
                console.log('getData...');
            }

            vm.loading = true;

            axios.get("/analytics/realtime")
            .then(response => {
                vm.loading = false;

                // console.log( response.data );
                vm.liveUser = response.data.live_users;
                vm.items = response.data.items;
                // vm.check_last_update();
                vm.runTimer();
            })
            .catch(error => {
                vm.loading = false;
                // console.log( error );
            })
        },

        date_format( d ){

            let y = d.substr(0, 4)
            let m = d.substr(4, 2)
            let day = d.substr(6, 2)

            let h = d.substr(8, 2)
            let i = d.substr(10, 2)

            // let theDate = new Date( parseInt(d)/10 )

            return y + '/' + m + '/' + day + " " + h+":"+i
        },

        check_last_update(){
            if( this.items.length == 0) return false;


            let item = this.items.find((n,i)=>i==0);

            this.last_update = this.date_format( item.date );
            // console.log('check_last_update', item);

        }

    }
}
</script>

<style lang="scss" scoped>
    .counter{
        font-size: 50px;
        margin-top: 13px;
    }

    table{
        width: 100%;

        td{
            padding-top: 2px;
            padding-bottom: 2px;

            border-top: 1px solid rgba(255,255,255,.1);
        }

        .td-date{
            width: 10px;
            white-space: nowrap;
        }
        .td-count{
            width: 10px;
            white-space: nowrap;
        }
    }

    .table-body-wrapper{
        min-height: 140px;
    }
</style>
