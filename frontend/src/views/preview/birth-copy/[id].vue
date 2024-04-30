<script setup>
import { onMounted } from "@vue/runtime-core";
import { useRoute, useRouter } from "vue-router";
import api from "@/utils/utilites";

const { params } = useRoute();
const data = ref({});
const router = useRouter();
const address = ref();

const fetchData = () => {
  api
    .post("preview-detail", {
      id: params.id,
      type: "births",
    })
    .then((response) => {
      data.value = response.data.data;
      address.value = response.data.address;
    })
    .catch((response) => {
      router.go(-1);
    });
};

onMounted(() => {
  fetchData();
});
</script>

<template>
  <div style="width: 100%">
    <!-- <VCard class=""> -->
    <div>
      <div class="d-flex justify-space-around">
        <table width="100%" style="border: none; margin: 10px 90px">
          <tr>
            <td style="border: none">
              <h3>
                រាជធានី ខេត្ត
                <span class="pl-2"> {{ address?.district?.province?.namekh }}</span>
              </h3>
              <h3 class="my-4">
                ក្រុង ស្រុក ខណ្ឌ
                <span class="pl-2">{{ address?.district?.namekh }}</span>
              </h3>
              <h3>
                ឃុំ សង្កាត់
                <span class="pl-2">
                  {{ address?.namekh }}
                </span>
              </h3>
            </td>
          </tr>
        </table>
      </div>
      <div class="text-center my-4">
        <h1>សេចក្តីចម្លងសំបុត្របញ្ជាក់កំណើត</h1>
      </div>

      <br />
      <!-- birth table -->

      <div class="mx-auto dark:border" style="border: 2px solid grey; width: 90%">
        <table width="100%" border="1 | 0">
          <colgroup>
            <col width="5%" />
            <col width="5%" />
            <col width="5%" />
            <col width="5%" />
            <col width="5%" />
            <col width="5%" />
            <col width="5%" />
            <col width="5%" />
            <col width="5%" />
            <col width="5%" />
            <col width="5%" />
            <col width="5%" />
            <col width="5%" />
            <col width="5%" />
            <col width="5%" />
            <col width="5%" />
            <col width="5%" />
            <col width="5%" />
            <col width="5%" />
            <col width="5%" />
          </colgroup>

          <tr>
            <td class="text-center" colspan="6">ចម្លងសំបុត្រកំណើតចេញពី</td>
            <td colspan="5">
              <p class="text-center">សៀវភៅកំណើត</p>
              <p class="mx-2">
                លេខ​ <span class="mx-5 mb-3">{{ data?.book }}</span>
              </p>
            </td>
            <td colspan="9">
              <p class="text-center">សំបុត្រកំណើត</p>
              <p class="mx-2">
                លេខ {{ data?.number }} ចុះថ្ងៃទី {{ data?.reg_date }} ខែ
                {{ data?.reg_month }} ឆ្នាំ {{ data?.reg_year }}
              </p>
            </td>
          </tr>

          <tr>
            <td colspan="6">
              <p class="ml-2 mt-2">នាមត្រកូល</p>
              <hr
                style="height: 2px; border-width: 0; color: gray; background-color: gray"
              />
              <p class="ml-2 mt-2">នាមខ្លួនសព</p>
            </td>
            <td class="text-center" colspan="12">
              <p class="mt-3">{{ data?.surname }}</p>
              <hr
                style="height: 2px; border-width: 0; color: gray; background-color: gray"
              />
              <p class="mt-3">{{ data?.name }}</p>
            </td>
            <td class="text-center" colspan="3">
              <p class="text-center">ភេទ</p>
              <p class="text-center">{{ data?.gender }}</p>
            </td>
          </tr>

          <tr>
            <td colspan="3">
              <p class="mt-2 mx-2">ជាអក្សរឡាតាំង</p>
            </td>
            <td colspan="3">
              <p class="mt-2 mx-2">នាមត្រកូល</p>
              <hr
                style="height: 2px; border-width: 0; color: gray; background-color: gray"
              />
              <p class="mt-2 mx-2">នាមខ្លួន</p>
            </td>
            <td class="text-center" colspan="15">
              <p class="mt-3">{{ data?.surname_latin }}</p>
              <hr
                style="height: 2px; border-width: 0; color: gray; background-color: gray"
              />
              <p class="mt-3">{{ data?.name_latin }}</p>
            </td>
          </tr>

          <tr>
            <td colspan="6">
              <p class="mt-2 mx-2">សញ្ជាតិ</p>
            </td>
            <td class="text-center" colspan="15">
              <p class="mt-3">ខ្មែរ</p>
            </td>
          </tr>

          <tr>
            <td colspan="6">
              <p class="mt-2 mx-2">ថ្ងៃ​ ខែ ឆ្នាំកំណើត</p>
            </td>
            <td class="text-center" colspan="15">
              <p class="mt-3">
                ថ្ងៃ {{ data?.day_birth }} ទី {{ data?.date_birth }} ខែ
                {{ data?.month_birth }} ឆ្នាំ {{ data?.year_birth }}
              </p>
            </td>
          </tr>

          <tr>
            <td colspan="6">
              <p class="text-center mt-2">ទីកន្លែងកំណើត</p>
              <p class="text-center mt-2">
                ភូមិ ឃុំ សង្កាត់ ស្រុក ខណ្ឌ ខេត្ត ក្រុង ប្រទេស
              </p>
            </td>
            <td class="text-center" colspan="15">
              <p>{{ data?.village }} {{ data?.commune }} {{ data?.district }}</p>
              <p>{{ data?.province }} ប្រទេសកម្ពុជា</p>
            </td>
          </tr>

          <tr>
            <td colspan="6" style="border-top: 3px solid gray">
              <p class="text-center mt-2">ឳពុក ម្តាយទារក</p>
            </td>
            <td colspan="7" style="border-top: 3px solid gray">
              <p class="text-center mt-2">ឳពុក</p>
            </td>
            <td colspan="7" style="border-top: 3px solid gray">
              <p class="text-center mt-2">ម្តាយ</p>
            </td>
          </tr>

          <tr>
            <td colspan="6">
              <p class="mt-2 mx-2">នាមត្រកូល និងនាមខ្លួន</p>
              <hr
                style="
                  height: 2px;
                  border-width: 0;
                  color: gray;
                  border-top: 1px dashed gray;
                "
              />
              <p class="mt-2 mx-2">ជាអក្សរឡាតាំង</p>
            </td>
            <td colspan="7">
              <p class="mx-2 text-center">{{ data?.dad }}</p>
              <div v-if="!data?.dad"><br /></div>
              <hr
                style="height: 2px; border-width: 0; color: gray; background-color: gray"
              />
              <div v-if="!data?.dad_latin"><br /></div>
              <p style="position: relative; top: 10px" class="text-center">
                {{ data?.dad_latin }}
              </p>
            </td>
            <td colspan="7">
              <p class="text-center">{{ data?.mum }}</p>
              <div v-if="!data?.mum"><br /></div>
              <hr
                style="height: 2px; border-width: 0; color: gray; background-color: gray"
              />
              <div v-if="!data?.mum_latin"><br /></div>
              <p class="text-center" style="position: relative; top: 10px">
                {{ data?.mum_latin }}
              </p>
            </td>
          </tr>

          <tr>
            <td colspan="6">
              <p class="mx-2 mt-2">សញ្ជាតិ</p>
            </td>
            <td colspan="7">
              <p class="text-center">ខ្មែរ</p>
            </td>
            <td colspan="7">
              <p class="text-center">ខ្មែរ</p>
            </td>
          </tr>

          <tr>
            <td colspan="6">
              <p class="mx-2 mt-2">ថ្ងៃ ខែ ឆ្នាំកំណើត</p>
            </td>
            <td colspan="7">
              <p class="text-center mx-2 mt-2">
                ថ្ងៃទី {{ data?.dad_date_birth }} ខែ {{ data?.dad_month_birth }} ឆ្នាំ
                {{ data?.dad_year_birth }}
              </p>
            </td>
            <td colspan="7">
              <p class="text-center mx-2 mt-2">
                ថ្ងៃទី {{ data?.mum_date_birth }} ខែ {{ data?.mum_month_birth }} ឆ្នាំ
                {{ data?.mum_year_birth }}
              </p>
            </td>
          </tr>

          <tr>
            <td colspan="6">
              <p class="text-center pt-5">ទីកន្លែងកំណើត</p>
              <p class="mx-2 pb-4">ភូមិ ឃុំ សង្កាត់ ស្រុក ខណ្ឌ ខេត្ត ក្រុង ប្រទេស</p>
            </td>
            <td colspan="7">
              <p class="text-center mt-5">
                {{ data?.dad_village }} {{ data?.dad_commune }}
              </p>
              <p class="text-center">
                {{ data?.dad_district }} {{ data?.dad_province }} ប្រទេ​សកម្ពុជា
              </p>
            </td>
            <td colspan="7">
              <p class="text-center mt-5">
                {{ data?.mum_village }} {{ data?.mum_commune }}
              </p>
              <p class="text-center">
                {{ data?.mum_district }} {{ data?.mum_province }} ប្រទេ​សកម្ពុជា
              </p>
            </td>
          </tr>
        </table>
      </div>
      <br />
      <div class="d-flex justify-space-around pt-5">
        <table width="100%" style="border: none; margin: 10px 90px">
          <tr>
            <td style="border: none">
              <p>
                លេខ​ .................................................................
              </p>
              <p>
                យើង ...................<span class="text-h5 font-weight-bold"
                  >អភិបាលក្រុង{{ address?.district?.namekh }}</span
                >...................
              </p>
              <p>
                បានឃើញ នឹង បញ្ចាក់ថា ហត្តលេខាខាងស្តាំនេះជាហត្តលេខា <br />របស់លោក
                <input
                  style="width: 125px; margin: 1rem 4em; height: 2rem"
                  type="text"
                />ជាមន្ត្រី​<br />...........<span class="text-h6 font-weight-bold"
                  >រក្សាសៀវភៅអត្រានុកូលដ្ឋាន​​​</span
                >..........​ពិតប្រាកដមែន។
              </p>
              <p>
                ធ្វើនៅ {{ address?.district?.namekh }} ថ្ងៃទី..............
                ខែ................. ឆ្នាំ.................
              </p>
            </td>
            <td style="border: none; vertical-align: top">
              <p>
                ធ្វើនៅ {{ address?.district?.namekh }} ថ្ងៃទី..................
                ខែ................. ឆ្នាំ.................
              </p>
              <p></p>
              <p></p>
              <p></p>
              <p class="mx-9" style="font-weight: bold; font-size: 20px">
                មន្ត្រី រក្សាសៀវភៅអត្រានុកូលដ្ឋាន
              </p>
            </td>
          </tr>
        </table>
      </div>
    </div>
    <!-- </VCard> -->
  </div>
</template>

<style scoped>
a {
  font-weight: bold;
  font-family: "Courier New", Courier, monospace;
}
table {
  border-collapse: collapse;
}
table,
th,
td {
  border: 2px solid gray;
}
</style>
