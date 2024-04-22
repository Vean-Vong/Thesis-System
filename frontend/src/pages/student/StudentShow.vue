<template>
    <div>
        <div class="q-mx-sm q-pa-md">
            <div class="bg-primary text-white shadow-1 text-bold rounded-borders q-px-sm q-py-xs text-h6 text-capitalize">
                Student Details
            </div>
            <br />
            <q-card>
                <div class="q-pa-md">
                    <div>
                        <q-btn :to="{ name: 'student.index' }" class="q-pr-sm" no-caps outline color="primary" dense>
                            <q-icon name="arrow_left" />
                            Back
                        </q-btn>
                    </div>


                    <div class="q-mt-lg" v-if="!loading">
                        <div class="row">
                            <div class=" col-12 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <div class="flex justify-center items-center full-height">
                                    <div>
                                        <div v-if="form.gender == 1">
                                            <img class="cus-avatar" src="../../../public/studentm.png" alt="" />
                                        </div>
                                        <div v-else>
                                            <img class="cus-avatar" src="../../../public/studentf.png" alt="" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="q-pa-md rounded-borders col-12 col-lg-8 col-md-6 col-sm-12 col-xs-12">
                                <div class="row q-pa-sm justify-center items-center" v-if="form.status_label == 'Active'">
                                    <div class="seperator_profile_green col-12 col-lg-2 col-md-4 col-sm-4">
                                        Profile
                                    </div>
                                    <div class="col-12 col-lg-10 col-md-8 col-sm-8">
                                        <div class="ruler_profile_green"></div>
                                    </div>
                                </div>
                                <div class="row q-pa-sm justify-center items-center" v-else>
                                    <div class="seperator_profile_red col-12 col-lg-2 col-md-4 col-sm-4">
                                        Profile
                                    </div>
                                    <div class="col-12 col-lg-10 col-md-8 col-sm-8">
                                        <div class="ruler_profile_red"></div>
                                    </div>
                                </div>
                                <div class="row justify-center">
                                    <div class="q-pa-sm col-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div>
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td>Code</td>
                                                        <td class="q-px-md">: <span class="q-px-xs">{{ form.code }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Name</td>
                                                        <td class="q-px-md">: <span class="q-px-xs">{{ form.full_name
                                                        }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Day of Birth</td>
                                                        <td class="q-px-md">: <span class="q-px-xs">{{ form.dob }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Gender</td>
                                                        <td class="q-px-md">: <span class="q-px-xs">{{ form.gender_label
                                                        }}</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Student status</td>
                                                        <td class="q-px-md">:
                                                            <span class="q-px-xs">
                                                                {{ form.student_status_label ? form.student_status_label :
                                                                    'N / A' }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Status</td>
                                                        <td class="q-px-md">:
                                                            <span class="q-px-xs Active-status"
                                                                v-if="form.status_label == 'Active'">
                                                                {{ form.status_label }}
                                                            </span>
                                                            <span class="q-px-xs inactive-status" v-else>
                                                                {{ form.status_label }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>From</td>
                                                        <td class="q-px-md">: <span class="q-px-xs">{{ form.from }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Phone number</td>
                                                        <td class="q-px-md">: <span class="q-px-xs">{{ form.phone }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Other</td>
                                                        <td class="q-px-md">: <span class="q-px-xs">{{ form.other }}</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row q-mt-sm">
                            <div class="q-pa-md  rounded-borders col-12 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="row q-pa-sm justify-center items-center">
                                    <div class="seperator_text col-12 col-lg-4 col-md-4 col-sm-4">
                                        Place of Birth
                                    </div>
                                    <div class="col-12 col-lg-8 col-md-8 col-sm-8">
                                        <div class="ruler"></div>
                                    </div>
                                </div>
                                <div class="row justify-center">
                                    <div class="q-pa-sm col-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div>
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td>Village</td>
                                                        <td class="q-px-md">: <span class="q-px-xs">{{ form.village_birth
                                                        }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Commune</td>
                                                        <td class="q-px-md">: <span class="q-px-xs">{{ form.commune_birth
                                                        }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>District</td>
                                                        <td class="q-px-md">: <span class="q-px-xs">{{ form.district_birth
                                                        }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Province</td>
                                                        <td class="q-px-md">: <span class="q-px-xs">{{ form.province_birth
                                                        }}</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="q-pa-md  rounded-borders col-12 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="row q-pa-sm justify-center items-center">
                                    <div class="seperator_text col-12 col-lg-4 col-md-4 col-sm-4">
                                        Address
                                    </div>
                                    <div class="col-12 col-lg-8 col-md-8 col-sm-8">
                                        <div class="ruler"></div>
                                    </div>
                                </div>
                                <div class="row justify-center">
                                    <div class="q-pa-sm col-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div>
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td>Village</td>
                                                        <td class="q-px-md">: <span class="q-px-xs">{{ form.village
                                                        }}</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Commune</td>
                                                        <td class="q-px-md">: <span class="q-px-xs">{{ form.commune
                                                        }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>District</td>
                                                        <td class="q-px-md">: <span class="q-px-xs">{{ form.district
                                                        }}</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Province</td>
                                                        <td class="q-px-md">: <span class="q-px-xs">{{ form.province
                                                        }}</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row q-mt-sm">
                            <div class="q-pa-md  rounded-borders col-12 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="row q-pa-sm justify-center items-center">
                                    <div class="seperator_text col-12 col-lg-4 col-md-4 col-sm-4">
                                        Father's Info
                                    </div>
                                    <div class="col-12 col-lg-8 col-md-8 col-sm-8">
                                        <div class="ruler"></div>
                                    </div>
                                </div>
                                <div class="row justify-center">
                                    <div class="q-pa-sm col-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div>
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td>Name</td>
                                                        <td class="q-px-md">: <span class="q-px-xs">{{ form.father_full_name
                                                        }}</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Job</td>
                                                        <td class="q-px-md">: <span class="q-px-xs">{{ form.d_job }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Phone number</td>
                                                        <td class="q-px-md">: <span class="q-px-xs">{{ form.d_phone_number
                                                        }}</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="q-pa-md  rounded-borders col-12 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="row q-pa-sm justify-center items-center">
                                    <div class="seperator_text col-12 col-lg-4 col-md-4 col-sm-4">
                                        Mother's Info
                                    </div>
                                    <div class="col-12 col-lg-8 col-md-8 col-sm-8">
                                        <div class="ruler"></div>
                                    </div>
                                </div>
                                <div class="row justify-center">
                                    <div class="q-pa-sm col-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div>
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td>Name</td>
                                                        <td class="q-px-md">: <span class="q-px-xs">{{ form.mother_full_name
                                                        }}</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Job</td>
                                                        <td class="q-px-md">: <span class="q-px-xs">{{ form.m_job }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Phone number</td>
                                                        <td class="q-px-md">: <span class="q-px-xs">{{ form.m_phone_number
                                                        }}</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row q-mt-sm">
                            <div class="q-pa-md  rounded-borders col-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row q-pa-sm justify-center items-center">
                                    <div class="seperator_text col-12 col-lg-2 col-md-2 col-sm-2">
                                        Guardian
                                    </div>
                                    <div class="col-12 col-lg-10 col-md-10 col-sm-10">
                                        <div class="ruler"></div>
                                    </div>
                                </div>
                                <div class="row justify-center">
                                    <div class="q-pa-sm col-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div>
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td>Name</td>
                                                        <td class="q-px-md">: <span class="q-px-sm">{{
                                                            form.guardian_full_name
                                                        }}</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Gender</td>
                                                        <td class="q-px-md">: <span class="q-px-sm">{{ form.g_gender_label
                                                        }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Job</td>
                                                        <td class="q-px-md">: <span class="q-px-sm">{{ form.g_job }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Phone number</td>
                                                        <td class="q-px-md">: <span class="q-px-sm">{{ form.g_phone_number
                                                        }}</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Other</td>
                                                        <td class="q-px-md">: <span class="q-px-sm">{{ form.g_detail
                                                        }}</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- skeleton  -->
                    <div class="q-mt-lg" v-else>
                        <div class="row">
                            <div class=" col-12 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <div class="flex justify-center items-center full-height">
                                    <div>
                                        <q-skeleton type="QAvatar" style="width: 250px; height:250px;" />
                                        <!-- <div class="No_img">
                                            <h4>No Photo</h4>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                            <div class="q-pa-md rounded-borders col-12 col-lg-8 col-md-6 col-sm-12 col-xs-12">
                                <div class="row q-px-sm justify-center items-center">
                                    <div class="col-12 q-px-sm">
                                        <q-skeleton type="text" />
                                    </div>
                                </div>
                                <div class="row justify-center">
                                    <div class="q-pa-sm col-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div>
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row q-mt-sm">
                            <div class="q-pa-md  rounded-borders col-12 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="row q-pa-sm justify-center items-center">
                                    <div class="col-12 q-px-sm">
                                        <q-skeleton type="text" />
                                    </div>
                                </div>
                                <div class="row justify-center">
                                    <div class="q-pa-sm col-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div>
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="q-pa-md  rounded-borders col-12 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="row q-pa-sm justify-center items-center">
                                    <div class="col-12 q-px-sm">
                                        <q-skeleton type="text" />
                                    </div>
                                </div>
                                <div class="row justify-center">
                                    <div class="q-pa-sm col-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div>
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row q-mt-sm">
                            <div class="q-pa-md  rounded-borders col-12 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="row q-pa-sm justify-center items-center">
                                    <div class="col-12 q-px-sm">
                                        <q-skeleton type="text" />
                                    </div>
                                </div>
                                <div class="row justify-center">
                                    <div class="q-pa-sm col-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div>
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="q-pa-md  rounded-borders col-12 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="row q-pa-sm justify-center items-center">
                                    <div class="col-12 q-px-sm">
                                        <q-skeleton type="text" />
                                    </div>
                                </div>
                                <div class="row justify-center">
                                    <div class="q-pa-sm col-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div>
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row q-mt-sm">
                            <div class="q-pa-md  rounded-borders col-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row q-pa-sm justify-center items-center">
                                    <div class="col-12 q-px-sm">
                                        <q-skeleton type="text" />
                                    </div>
                                </div>
                                <div class="row justify-center">
                                    <div class="q-pa-sm col-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div>
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                        <td class="profile_data_skeleton">
                                                            <q-skeleton type="text" />
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Real Data -->
                </div>
            </q-card>
        </div>
    </div>
</template>
<script>
import axiosApiInstance from '../../utilites';
export default {
    data: () => ({
        loading: false,
        axiosApiInstance: axiosApiInstance,
        form: {
            code: '',
            full_name: '',
            full_name_latin: '',
            gender_label: '',
            dob: '',
            other: '',
            from: '',
            gender: '',
            photo_path: '',
            student_status_label: '',
            status_label: '',
            village: '',
            commune: '',
            district: '',
            province: '',
            village_birth: '',
            commune_birth: '',
            district_birth: '',
            province_birth: '',
            mother_full_name: '',
            m_job: '',
            m_phone_number: '',
            father_full_name: '',
            d_job: '',
            d_phone_number: '',
            guardian_full_name: '',
            g_gender_label: '',
            g_phone_number: '',
            g_job: '',
            g_detail: '',
        },

    }),
    methods: {
        fetchStudentDetail() {
            this.loading = true;
            this.axiosApiInstance.get(`/students/${this.$route.params.student}`)
                .then((res) => {
                    console.log(res.data.data);
                    this.form = res.data.data;
                }).catch((err) => {
                    console.log(err);
                }).finally(() => {
                    this.loading = false;
                })
        }
    },
    mounted() {
        this.fetchStudentDetail();
    }
};
</script>

<style scoped>
.cus-avatar {
    width: 250px;
    height: 250px;
    object-fit: cover;
    padding: 30px;
    border: 2px solid #1976d2;
    border-radius: 100%;
}

.No_img {
    width: 250px;
    height: 250px;
    border-radius: 100%;
    display: grid;
    place-content: center;
    background-color: #e5e7eb;
}

.No_img h4 {
    color: #f3f4f6;
}

/* .flex-row {
    display: grid;
    padding-left: 10px;
}

.title-of {
    margin: 5px;
}

.data-show {
    margin: 5px;
} */

.ruler {
    border: #9ca3af solid 2px;
    border-radius: 0px 10px 10px 0px;
}

.seperator_text {
    background-color: #9ca3af;
    text-align: center;
    color: white;
    letter-spacing: 1px;
    border-radius: 10px;
}

.ruler_profile_green {
    border: #22c55e solid 2px;
    border-radius: 0px 50px 50px 0px;
}

.seperator_profile_red {
    background-color: #ef4444;
    /* background-color: #1976d2; */
    text-align: center;
    color: white;
    letter-spacing: 1px;
    border-radius: 10px;
}

.ruler_profile_red {
    border: #ef4444 solid 2px;
    border-radius: 0px 50px 50px 0px;
}

.seperator_profile_green {
    background-color: #22c55e;
    /* background-color: #1976d2; */
    text-align: center;
    color: white;
    letter-spacing: 1px;
    border-radius: 10px;
}

.Active-status {
    background-color: #22c55e;
    color: white;
    border-radius: 20px;
    text-transform: capitalize;
    font-weight: bold;
    padding: 3px 10px 3px 10px;
    font-size: small;
    letter-spacing: 1px;
}

.inactive-status {
    background-color: #ef4444;
    color: white;
    border-radius: 20px;
    text-transform: capitalize;
    font-weight: bold;
    padding: 3px 10px 3px 10px;
    font-size: small;
    letter-spacing: 1px;
}

@media (min-width:300px) and (max-width: 600px) {
    .ruler {
        display: none;
    }

    .ruler_profile_red {
        display: none;
    }

    .ruler_profile_green {
        display: none;
    }
}

.profile_data_skeleton {
    width: 100px;
    padding: 0px 10px 0px 10px;
}
</style>
