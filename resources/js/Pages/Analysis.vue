<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/inertia-vue3';
import { reactive, onMounted } from 'vue';
import { getToday } from '@/common';
import Chart from '@/Components/Chart.vue';
import ResultTable from '@/Components/ResultTable.vue';

onMounted(() =>{
    form.startDate = getToday()
    form.endDate = getToday()
})

const form = reactive({
    startDate: null,
    endDate: null,
    type: 'perDay',
    rfmPrms: [
        14, 28, 60, 90, 7, 5, 3, 2, 300000, 200000, 100000, 30000
    ],
})

const data = reactive({})

const getData = async () => {
    try{
    await axios.get('/api/analysis/', {
        params: {
        startDate: form.startDate,
        endDate: form.endDate,
        type: form.type,
        rfmPrms: form.rfmPrms
    }
    })
    .then( res => {
        data.data = res.data.data
        if(res.data.labels) {data.labels = res.data.labels}
        if(res.data.eachCount) {data.eachCount = res.data.eachCount}
        data.totals = res.data.totals
        data.type = res.data.type
        console.log(res.data)
    })
    } catch (e){
        console.log(e.message)
    }
}
</script>

<template>
    <Head title="データ分析" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">データ分析</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="getData">
                            分析方法<br>
                            <input type="radio" v-model="form.type" value="perDay" checked><span class="mr-2">日別</span>
                            <input type="radio" v-model="form.type" value="perMonth"><span class="mr-2">月別</span>
                            <input type="radio" v-model="form.type" value="perYear"><span class="mr-2">年別</span>
                            <input type="radio" v-model="form.type" value="decile"><span class="mr-2">デシル分析</span>
                            <input type="radio" v-model="form.type" value="rfm"><span class="mr-2">RFM分析</span>
                            <br>
                            From: <input type="date" name="startDate" v-model="form.startDate">
                            To: <input type="date" name="endDate" v-model="form.endDate">
                            <br>
                            
                            <div v-if="form.type === 'decile'" class="mt-3">
                                <h3>デシル分析とは</h3>
                                <p>デシル分析は、顧客や商品のデータを売上や利益などの指標に基づいて10等分（デシル）に分け、各グループの特徴を分析する手法です。上位10%を「デシル1」、次の10%を「デシル2」…と分類し、特に上位グループの貢献度や下位グループの改善点を把握します。</p>
                            </div>

                            <div v-if="form.type === 'rfm'" class="mt-3">
                                <h3>RFM分析とは</h3>
                                <p>RFM分析は、顧客を「購入履歴」に基づいて評価し、顧客の価値を分類・分析するマーケティング手法です。「Recency（最近の購入時期）」「Frequency（購入頻度）」「Monetary（購入金額）」の3つの指標を用います。</p>
                            </div>

                            <div v-if="form.type === 'rfm'" class="my-8">
                                <table class="mx-auto">
                                    <thead>
                                        <tr>
                                            <th>ランク</th>
                                            <th>R (○日以内)</th>
                                            <th>F (○回以上)</th>
                                            <th>M (○円以上)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>5</td>
                                            <td><input type="number" v-model="form.rfmPrms[0]"></td>
                                            <td><input type="number" v-model="form.rfmPrms[4]"></td>
                                            <td><input type="number" v-model="form.rfmPrms[8]"></td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td><input type="number" v-model="form.rfmPrms[1]"></td>
                                            <td><input type="number" v-model="form.rfmPrms[5]"></td>
                                            <td><input type="number" v-model="form.rfmPrms[9]"></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td><input type="number" v-model="form.rfmPrms[2]"></td>
                                            <td><input type="number" v-model="form.rfmPrms[6]"></td>
                                            <td><input type="number" v-model="form.rfmPrms[10]"></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td><input type="number" v-model="form.rfmPrms[3]"></td>
                                            <td><input type="number" v-model="form.rfmPrms[7]"></td>
                                            <td><input type="number" v-model="form.rfmPrms[11]"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <button class="flex mt-3 mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">分析する</button>
                        </form>

                        <div v-show="data.data">
                            <div v-if="data.type != 'rfm'">
                                <Chart :data="data" />
                            </div>
                            <ResultTable :data="data" />
                        </div>

                        
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
