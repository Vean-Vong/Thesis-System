<script setup>
import { onMounted, ref } from "@vue/runtime-core";
import { useRoute, useRouter } from "vue-router";
import api from "@/utils/utilites";

const { params } = useRoute();
const router = useRouter();
const data = ref({});
const address = ref({});
const fetchData = () => {
  api
    .post("preview-detail", {
      id: params.id,
      type: "married",
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
    <div>
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
      <h1>សេចក្តីចម្លងសំបុត្របញ្ជាក់អាពាហ៍ពិពាហ៍</h1>
    </div>
    <br />
    <div class="mx-auto dark:border" style="border: 2px solid grey; width: 90%">
      <!-- married table -->
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
          <td class="text-center" colspan="6">ចម្លងសំបុត្រអាពាហ៍ពិពាហ៍ចេញពី</td>
          <td colspan="5" class="pa-3">
            <p class="text-center">សៀវភៅអាពាហ៍ពិពាហ៍</p>
            <p class="mx-2">លេខ {{ data?.book }}</p>
          </td>
          <td colspan="9">
            <p class="text-center">សំបុត្រអាពាហ៍ពិពាហ៍</p>
            <p class="mx-2">
              លេខ {{ data?.number }} ចុះថ្ងៃទី {{ data?.reg_date }} ខែ
              {{ data?.reg_month }} ឆ្នាំ {{ data?.reg_year }}
            </p>
          </td>
        </tr>
        <tr class="text-center">
          <td colspan="6"><p class="mt-2">អំពី ប្តីប្រពន្ធ</p></td>
          <td colspan="6"><p class="mt-2">ប្តី</p></td>
          <td colspan="8"><p class="mt-2">ប្រពន្ធ</p></td>
        </tr>

        <tr>
          <td colspan="6"><p class="ml-2 mt-2">នាមត្រកូល</p></td>
          <td class="text-center" colspan="6">
            <p class="mt-3">{{ data?.h_surname }}</p>
          </td>
          <td class="text-center" colspan="9">
            <p class="mt-3">{{ data?.w_surname }}</p>
          </td>
        </tr>

        <tr>
          <td colspan="6"><p class="ml-2 mt-2">នាមខ្លួន</p></td>
          <td class="text-center" colspan="6">
            <p class="mt-3">{{ data?.h_name }}</p>
          </td>
          <td class="text-center" colspan="9">
            <p class="mt-3">{{ data?.w_name }}</p>
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
          <td class="text-center" colspan="6">
            <p>{{ data?.h_surname_latin }}</p>
            <div v-if="!data?.h_surname_latin"><br /></div>
            <hr
              style="height: 2px; border-width: 0; color: gray; background-color: gray"
            />
            <div v-if="!data?.h_name_latin"><br /></div>
            <p style="position: relative; top: 10px">{{ data?.h_name_latin }}</p>
          </td>
          <td class="text-center" colspan="9">
            <p>{{ data?.w_surname_latin }}</p>
            <div v-if="!data?.w_surname_latin"><br /></div>
            <hr
              style="height: 2px; border-width: 0; color: gray; background-color: gray"
            />
            <div v-if="!data?.w_name_latin"><br /></div>
            <p style="position: relative; top: 10px">{{ data?.w_name_latin }}</p>
          </td>
        </tr>

        <tr>
          <td colspan="6"><p class="ml-2 mt-2">សញ្ជាតិ</p></td>
          <td class="text-center" colspan="6">
            <p class="mt-3">ខ្មែរ</p>
          </td>
          <td class="text-center" colspan="9">
            <p class="mt-3">ខ្មែរ</p>
          </td>
        </tr>

        <tr>
          <td colspan="6"><p class="ml-2 mt-2">ថ្ងៃ ខែ ឆ្នាំកំណើត</p></td>
          <td class="text-center" colspan="6">
            <p class="mt-3">
              ថ្ងៃ {{ data?.h_day_birth }} ទី​​ {{ data?.h_date_birth }} ខែ
              {{ data?.h_month_birth }} ឆ្នាំ {{ data?.h_year_birth }}
            </p>
          </td>
          <td class="text-center" colspan="9">
            <p class="mt-3">
              ថ្ងៃ {{ data?.w_day_birth }} ទី​​ {{ data?.w_date_birth }} ខែ
              {{ data?.w_month_birth }} ឆ្នាំ {{ data?.w_year_birth }}
            </p>
          </td>
        </tr>

        <tr>
          <td colspan="6">
            <p class="text-center mt-2">ទីកន្លែងកំណើត</p>
            <p class="text-center mt-2">ភូមិ ឃុំ សង្កាត់ ក្រុង ស្រុក ខណ្ឌ</p>
            <p class="text-center mt-2">រាជធានី ខេត្ត ប្រទេស</p>
          </td>
          <td class="text-center" colspan="6">
            <p class="mt-3">
              {{ data?.h_village }} {{ data?.h_commune }} {{ data?.h_district }}
            </p>
            <p class="mt-3">{{ data?.h_province }} ប្រទេសកម្ពុជា</p>
          </td>
          <td class="text-center" colspan="9">
            <p class="mt-3">
              {{ data?.w_village }} {{ data?.w_commune }} {{ data?.w_district }}
            </p>
            <p class="mt-3">{{ data?.w_province }} ប្រទេសកម្ពុជា</p>
          </td>
        </tr>

        <tr>
          <td colspan="6"><p class="ml-2 mt-2">មុខរបរពេលចុះបញ្ជីអាពាហ៍ពិពាហ៍</p></td>
          <td class="text-center" colspan="6">
            <p class="mt-3">{{ data?.h_job }}</p>
          </td>
          <td class="text-center" colspan="9">
            <p class="mt-3">{{ data?.w_job }}</p>
          </td>
        </tr>

        <tr>
          <td colspan="6">
            <p class="ml-2 mt-2 py-3">ទីលំនៅពេលចុះបញ្ជីអាពាហ៍ពិពាហ៍</p>
          </td>
          <td class="text-center" colspan="6">
            <p class="mt-3">
              {{ data?.h_current_village }} {{ data?.h_current_commune }}
              {{ data?.h_current_district }}
            </p>
            <p class="mt-3">{{ data?.h_current_province }} ប្រទេសកម្ពុជា</p>
          </td>
          <td class="text-center" colspan="9">
            <p class="mt-3">
              {{ data?.w_current_village }} {{ data?.w_current_commune }}
              {{ data?.w_current_district }}
            </p>
            <p class="mt-3">{{ data?.w_current_province }} ប្រទេសកម្ពុជា</p>
          </td>
        </tr>

        <tr>
          <td colspan="6">
            <p class="ml-2 mt-2">ថ្ងៃ ខែ ឆ្នាំចុះបញ្ជីអាពាហ៍ពិពាហ៍</p>
          </td>
          <td class="text-center" colspan="15">
            <p>{{ data?.reg_full_date }}</p>
          </td>
        </tr>

        <tr>
          <td colspan="6"><p class="ml-2 mt-2">ទីកន្លែងចុះបញ្ជីអាពាហ៍ពិពាហ៍</p></td>
          <td class="text-center" colspan="15">
            <p>{{ data?.reg_full_place }}</p>
          </td>
        </tr>

        <tr class="text-center">
          <td colspan="6" style="border-top: 3px solid gray">
            <p class="mt-2">អំពី ឪពុកម្តាយ</p>
          </td>
          <td colspan="6" style="border-top: 3px solid gray">
            <p class="mt-2">ខាងប្តី</p>
          </td>
          <td colspan="8" style="border-top: 3px solid gray">
            <p class="mt-2">ខាងប្រពន្ធ</p>
          </td>
        </tr>

        <tr>
          <td colspan="6">
            <p class="ml-2 mt-2">នាមត្រកូល និងនាមខ្លួនឪពុក</p>
            <hr
              style="
                height: 2px;
                border-width: 0;
                color: gray;
                border-top: 1px dashed gray;
              "
            />
            <p class="ml-2 mt-2">ជាអក្សរឡាតាំង</p>
          </td>
          <td class="text-center" colspan="6">
            <p>{{ data?.h_dad }}</p>
            <div v-if="!data?.h_dad"><br /></div>
            <hr
              style="height: 2px; border-width: 0; color: gray; background-color: gray"
            />
            <div v-if="!data?.h_dad_latin"><br /></div>
            <p style="position: relative; top: 10px">{{ data?.h_dad_latin }}</p>
          </td>
          <td class="text-center" colspan="9">
            <p>{{ data?.w_dad }}</p>
            <div v-if="!data?.w_dad"><br /></div>
            <hr
              style="height: 2px; border-width: 0; color: gray; background-color: gray"
            />
            <div v-if="!data?.w_dad_latin"><br /></div>
            <p style="position: relative; top: 10px">{{ data?.w_dad_latin }}</p>
          </td>
        </tr>

        <tr>
          <td colspan="6"><p class="ml-2 mt-2">សញ្ជាតិ</p></td>
          <td class="text-center" colspan="6">
            <p class="mt-3">ខ្មែរ</p>
          </td>
          <td class="text-center" colspan="9">
            <p class="mt-3">ខ្មែរ</p>
          </td>
        </tr>

        <tr>
          <td colspan="6"><p class="ml-2 mt-2">ថ្ងៃ ខែ ឆ្នាំកំណើត</p></td>
          <td class="text-center" colspan="6">
            ថ្ងៃទី {{ data?.h_dad_date_birth }} ខែ {{ data?.h_dad_month_birth }} ឆ្នាំ
            {{ data?.h_dad_year_birth }}
          </td>
          <td class="text-center" colspan="9">
            ថ្ងៃទី {{ data?.w_dad_date_birth }} ខែ {{ data?.w_dad_month_birth }} ឆ្នាំ
            {{ data?.w_dad_year_birth }}
          </td>
        </tr>

        <tr>
          <td colspan="6">
            <p class="ml-2 mt-2">នាមត្រកូល និងនាមខ្លួនម្តាយ</p>
            <hr
              style="
                height: 2px;
                border-width: 0;
                color: gray;
                border-top: 1px dashed gray;
              "
            />
            <p class="ml-2 mt-2">ជាអក្សរឡាតាំង</p>
          </td>
          <td class="text-center" colspan="6">
            <p>{{ data?.h_mum }}</p>
            <div v-if="!data?.h_mum"><br /></div>
            <hr
              style="height: 2px; border-width: 0; color: gray; background-color: gray"
            />
            <div v-if="!data?.h_mum_latin"><br /></div>
            <p style="position: relative; top: 10px">{{ data?.h_mum_latin }}</p>
          </td>
          <td class="text-center" colspan="9">
            <p>{{ data?.w_mum }}</p>
            <div v-if="!data?.w_mum"><br /></div>
            <hr
              style="height: 2px; border-width: 0; color: gray; background-color: gray"
            />
            <div v-if="!data?.w_mum_latin"><br /></div>
            <p style="position: relative; top: 10px">{{ data?.w_mum_latin }}</p>
          </td>
        </tr>
        <tr>
          <td colspan="6"><p class="ml-2 mt-2">សញ្ជាតិ</p></td>
          <td class="text-center" colspan="6">
            <p class="mt-3">ខ្មែរ</p>
          </td>
          <td class="text-center" colspan="9">
            <p class="mt-3">ខ្មែរ</p>
          </td>
        </tr>

        <tr>
          <td colspan="6"><p class="ml-2 mt-2">ថ្ងៃ ខែ ឆ្នាំកំណើត</p></td>
          <td class="text-center" colspan="6">
            ថ្ងៃទី {{ data?.h_mum_date_birth }} ខែ {{ data?.h_mum_month_birth }} ឆ្នាំ
            {{ data?.h_mum_year_birth }}
          </td>
          <td class="text-center" colspan="9">
            ថ្ងៃទី {{ data?.w_mum_date_birth }} ខែ {{ data?.w_mum_month_birth }} ឆ្នាំ
            {{ data?.w_mum_year_birth }}
          </td>
        </tr>
      </table>
    </div>

    <div class="d-flex justify-space-around pt-5">
      <table width="100%" style="border: none; margin: 10px 90px">
        <tr>
          <td style="border: none">
            <p>លេខ​ .................................................................</p>
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
