<template>
    <section id="main-table-lids" class="section-pd">
        <div class="container mx-auto">
            <div class="panel-first">
                <div class="md:flex lg:flex">
                    <div>
                        <div class="inline-flex group-btn" role="group">
                            <button
                                type="button"
                                class="btn-group"
                                data-te-ripple-init
                                data-te-ripple-color="light"
                                @click="filteringBy('now')"
                            >
                                Сегодня
                            </button>
                            <button
                                type="button"
                                class="btn-group"
                                data-te-ripple-init
                                data-te-ripple-color="light"
                                @click="filteringBy('yesterday')"
                            >
                                Вчера
                            </button>
                            <button
                                type="button"
                                class="btn-group"
                                data-te-ripple-init
                                data-te-ripple-color="light"
                                @click="filteringBy('week')"
                            >
                                Неделя
                            </button>
                            <button
                                type="button"
                                class="btn-group"
                                data-te-ripple-init
                                data-te-ripple-color="light"
                                @click="filteringBy('mounth')"
                            >
                                Месяц
                            </button>
                            <button
                                type="button"
                                class="btn-group"
                                data-te-ripple-init
                                data-te-ripple-color="light"
                                @click="filteringBy('thremounth')"
                            >
                                Квартал
                            </button>
                            <button
                                type="button"
                                class="btn-group"
                                data-te-ripple-init
                                data-te-ripple-color="light"
                                @click="filteringBy('year')"
                            >
                                Год
                            </button>
                            <button
                                type="button"
                                class="btn-group"
                                data-te-ripple-init
                                data-te-ripple-color="light"
                                @click="clearFilterDate"
                            >
                                Сбросить фильтр
                            </button>
                        </div>
                    </div>
                    <div class="md:ml-4 lg:ml-4 w-260">
                        <VueDatePicker
                            v-model="selectedDatepicker"
                            @date-update="doSelectDatepicker"
                            @cleared="clearDatepicker"
                            class="datepicker-wrp"
                            month-name-format="long"
                            :enable-time-picker="false"
                            :day-names="[
                                'Пн',
                                'Вт',
                                'Ср',
                                'Чт',
                                'Пт',
                                'Сб',
                                'Вс',
                            ]"
                            :month-names="[
                                'Январь',
                                'Феваль',
                                'Ср',
                                'Чт',
                                'Пт',
                                'Сб',
                                'Вс',
                                'А',
                                'C',
                                'А',
                                'C',
                                'C',
                            ]"
                            range
                            ><template #input-icon>
                                <img
                                    class="input-slot-image"
                                    src="@/assets/mdi_calendar-today-outline.svg"
                                /> </template
                        ></VueDatePicker>
                    </div>
                </div>
            </div>
            <div class="panel-second">
                <div class="md:flex lg:flex">
                    <div class="mb:w-1/4 lg:w-1/4 mr-2 input-wrp">
                        <div class="flex space-x-4 input-text">
                            <div
                                class="flex rounded-md overflow-hidden w-full h-full"
                            >
                                <input
                                    type="text"
                                    class="input-search"
                                    placeholder="Искать по номеру телефона"
                                    v-model="filterPhone"
                                />
                                <button
                                    class="bg-primary-1 border border-primary-1 shadow-primary hover:bg-primary-2 hover:border hover:border-primary-1 text-white rounded-r-md"
                                >
                                    Найти
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="md:w-1/4 lg:w-1/4 mr-2 input-wrp">
                        <div class="flex space-x-4 input-text">
                            <div
                                class="flex rounded-md overflow-hidden w-full h-full"
                            >
                                <input
                                    type="text"
                                    class="input-search"
                                    placeholder="Фильтровать по UTM-метке"
                                    v-model="filterUtm"
                                />
                                <button
                                    @click="filterAnother('utm')"
                                    class="bg-primary-1 border border-primary-1 shadow-primary hover:bg-primary-2 hover:border hover:border-primary-1 text-white"
                                >
                                    Найти
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="mb:w-1/4 lg:w-1/4">
                        <div
                            class="navbar-right relative h-full flex input-list"
                        >
                            <button
                                class="flex justify-between w-full items-center px-6 whitespace-nowrap border bg-white h-16 rounded-md border-gray-2 hover:bg-white hover:border-primary-3 focus:bg-white focus:border-primary-3 leading-normal transition duration-150 ease-in-out motion-reduce:transition-none"
                                type="button"
                                id="dropdownMenuSmallButton"
                                data-te-dropdown-toggle-ref
                                aria-expanded="false"
                                data-te-ripple-init
                                data-te-ripple-color="light"
                                @click="isOpen = !isOpen"
                            >
                                Выбрать статус
                                <span class="ml-2 w-2">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                        class="h-5 w-5"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </span>
                            </button>

                            <div
                                v-if="isOpen"
                                class="absolute top-14 left-0 w-full rounded-b-md border-gray-1 bg-white border overflow-hidden"
                            >
                                <div
                                    class="px-5 py-2.5 bg-white hover:bg-gray-3 block cursor-pointer"
                                >
                                    Account Settings
                                </div>
                                <div
                                    class="px-5 py-2.5 bg-white hover:bg-gray-3 block cursor-pointer"
                                >
                                    Account Settings
                                </div>
                                <div
                                    class="px-5 py-2.5 bg-white hover:bg-gray-3 block cursor-pointer"
                                >
                                    Account Settings
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div
                data-te-perfect-scrollbar-init
                data-te-suppress-scroll-x="true"
                class="overflow-x-auto"
            >
                <div class="flex flex-col max-w-none">
                    <div class="sm:-mx-6 lg:-mx-8">
                        <div
                            class="inline-block min-w-full py-2 sm:px-6 lg:px-8"
                        >
                            <div class="overflow-x-auto">
                                <table
                                    class="min-w-full text-left text-sm table-fixed border-l border-gray-1"
                                >
                                    <thead
                                        class="border-b font-medium dark:border-neutral-500"
                                    >
                                        <tr>
                                            <th
                                                scope="col"
                                                v-for="(
                                                    item, index
                                                ) in titleTable"
                                                :key="index"
                                                :style="{
                                                    width:
                                                        item.length + 30 + 'px',
                                                }"
                                            >
                                                {{ item }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="item in filteredLeads"
                                            :key="item.id"
                                            class="border-b dark:border-neutral-500 hover:bg-neutral-100"
                                        >
                                            <td
                                                class="border-r whitespace-nowrap"
                                            >
                                                {{ item.create_date }}
                                            </td>
                                            <td
                                                class="border-r whitespace-nowrap"
                                            >
                                                {{ item.project }}

                                                <span
                                                    class="stage"
                                                    :style="{
                                                        background:
                                                            item.statusColor,
                                                    }"
                                                    >{{ item.status1 }}</span
                                                >
                                            </td>
                                            <td
                                                class="border-r whitespace-nowrap"
                                            >
                                                {{ item.project }}
                                            </td>
                                            <td
                                                class="border-r whitespace-nowrap"
                                            >
                                                <span
                                                    class="name-leads blue-text"
                                                >
                                                    <router-link
                                                        :to="{
                                                            name: 'lead',
                                                            params: {
                                                                id: item.id,
                                                            },
                                                        }"
                                                    >
                                                        {{ item.name }}
                                                    </router-link>
                                                </span>
                                            </td>
                                            <td
                                                class="border-r whitespace-nowrap"
                                            >
                                                <span class="stage">{{
                                                    item.status1
                                                }}</span>
                                            </td>
                                            <td
                                                class="border-r whitespace-nowrap"
                                            >
                                                <span class="blue-text">{{
                                                    item.info4
                                                }}</span>
                                            </td>
                                            <td
                                                class="border-r whitespace-nowrap"
                                            >
                                                <span class="blue-text">{{
                                                    item.scr
                                                }}</span>
                                            </td>
                                            <td
                                                class="border-r whitespace-nowrap"
                                            >
                                                <span class="blue-text">{{
                                                    item.info3
                                                }}</span>
                                            </td>
                                            <td
                                                class="border-r whitespace-nowrap"
                                            >
                                                <span class="blue-text">{{
                                                    item.info3
                                                }}</span>
                                            </td>
                                            <td
                                                class="border-r whitespace-nowrap"
                                            >
                                                {{ item.qlf }}
                                            </td>
                                            <td
                                                class="border-r whitespace-nowrap"
                                            >
                                                {{ item.utmSource }}
                                            </td>
                                            <td
                                                class="border-r whitespace-nowrap"
                                            >
                                                <span class="blue-text">{{
                                                    item.utmMedium
                                                }}</span>
                                            </td>
                                            <td
                                                class="border-r whitespace-nowrap"
                                            >
                                                {{ item.utmCampaign }}
                                            </td>
                                            <td
                                                class="border-r whitespace-nowrap"
                                            >
                                                <span class="blue-text">{{
                                                    item.utmContent
                                                }}</span>
                                            </td>
                                            <td
                                                class="border-r whitespace-nowrap"
                                            >
                                                {{ item.utmTerm }}
                                            </td>
                                            <td
                                                class="border-r whitespace-nowrap"
                                            >
                                                <span
                                                    class="name-leads blue-text"
                                                    >{{ item.roistat }}</span
                                                >
                                            </td>
                                            <td
                                                class="border-r whitespace-nowrap"
                                            >
                                                {{ item.typeType }}
                                            </td>
                                            <td
                                                class="border-r whitespace-nowrap"
                                            >
                                                {{ item.metrag }}
                                            </td>
                                            <td class="whitespace-nowrap">
                                                {{ item.info1 }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pagination flex">
               
                <div class="paginate-info mt-5">
                    <div class="paginate-item">Текущая страница:{{ leadCurrentPage }}</div>
                    <div class="paginate-item">Всего страниц: {{ leadTotalPages  }}</div>
                    <div class="paginate-item btn-more">Загрузить еще</div>
                </div>
                
                
                <nav
                    class="mt-5 flex items-center text-sm justify-end"
                    aria-label="Page navigation example"
                >
                
                    <div class="relative m-[2px] mb-3 flex align-baseline">
                        <label for="inputFilter" class="flex center"
                            >Отображать строк:</label
                        >
                        <select
                            id="inputFilter"
                            class="block w-30 ml-2 rounded-lg border-gray-2 p-2 text-sm hover:border-gray-2 focus:border-gray-2 focus:outline-none focus:ring-1 hover:bg-gray-1"
                        >
                            <option value="1" selected class="hover:bg-gray-1">
                               50
                            </option>
                           
                        </select>
                    </div>
                </nav>
            </div>
        </div>
    </section>
</template>
<script setup lang="ts">
import { computed, onMounted, ref, watch } from "vue";
import { defineStore, mapStores, storeToRefs } from "pinia";
// import { Datetimepicker, initTE } from "tw-elements";
import VueDatePicker from "@vuepic/vue-datepicker";
import { useLeadStore } from "../../entities/leads/store";
import "@vuepic/vue-datepicker/dist/main.css";
import { ja } from "date-fns/locale";
import {
    fiveYearsAgo,
    oneMonthAgo,
    oneYearsAgo,
    sevenDayAgo,
    threeMonthAgo,
    yesterdayDay,
} from "@/shared/composables/useDate";
import { LeadsModel } from "@/entities/leads/model";
import { LeadField, Statuses } from "@/entities/leads/types";

const { leads, selectedLeadId, statuses, leadFields, leadIds,leadTotalPages,leadCurrentPage } = storeToRefs(
    useLeadStore()
);
const LeadStore = useLeadStore();
const blocks = ref(true);
const isOpen = ref(false);
const selectedDate = ref("All");
const pipelineObj = [
    { pipeline_id: 1575012, name: "Воронка 1" },
    { pipeline_id: 3497622, name: "Воронка 2" },
    { pipeline_id: 7871932, name: "Воронка 3" },
    
];

const dataCustomFields = [
  
    {
        field_id: 9258213,
        field_name: "utm_medium",
        field_var: "utm_medium",
    },

    {
        field_id: 9258211,
        field_name: "utm_source",
        field_var: "utm_source",
    },

    
    {
        field_id: 9258219,
        field_name: "utm_content",
        field_var: "utm_content",
    },
  
    {
        field_id: 9258215,
        field_name: "utm_campaign",
        field_var: "utm_campaign",
    },

   
    {
        field_id: 9258217,
        field_name: "utm_term",
        field_var: "utm_term",
    },

    {
        field_id: 111479,
        field_name: "roistat",
        field_var: "roistat",
    },
   
    {
        field_id: 2222893,
        field_name: "Метраж (м2)",
        field_var: "metrag",
    },
    {
        field_id: 5450907,
        field_name: "Тип",
        field_var: "type_type",
    },
  
    {
        field_id: 9000635,
        field_name: "Встреча",
        field_var: "info1",
    },

    {
        field_id: 3210423,
        field_name: "info4",
        field_var: "info4",
    },
    {
        field_id: 87650725,
        field_name: "Причина",
        field_var: "klf",
    },
    {
        field_id: 3333907,
        field_name: "Информация 4",
        field_var: "info4",
    },
    {
        field_id: 2210365,
        field_name: "info3",
        field_var: "info3",
    },
    {
        field_id: 9234483,
        field_name: "Статус 1",
        field_var: "status1",
    },
];
const tableDataArray = computed(() => {
    return leads.value?.map((lead: any) => {
        let projectValue = pipelineObj.find(
            (item: any) => item.pipeline_id == lead.pipeline_id
        );
        let status = statuses.value?.find(
            (status: Statuses) => status.status_id === lead.status_id
        );

        let raw = JSON.parse(lead.raw_content);

        let extractedFields = extractCustomFields(
            dataCustomFields,
            raw.custom_fields_values || []
        );

        return {
            id: lead.id,
            name: lead.name,
            project: projectValue ? projectValue.name : null,
            scr: "...",
            info4: extractedFields.info4,
            status1: status ? status.name : "",
            statusColor: status ? status.color : "",
            statusCall: extractedFields.status_call,
            create_date: doDateConvert(lead.created_at_amo),
            utmMedium: extractedFields.Utm_medium,
            utmSource: extractedFields.Utm_source,
            utmContent: extractedFields.Utm_content,
            utmCampaign: extractedFields.Utm_campaign,
            utmTerm: extractedFields.Utm_term,
            roistat: extractedFields.Roistat,
            metrag: extractedFields.metrag,
            typeType: extractedFields.type_type,
            qlf: extractedFields.klf,
            info1: extractedFields.info1,
            info2: extractedFields.info2,
            info3: extractedFields.info3,
        };
    });
});

// извлечения значений по массиву field_id
const extractCustomFields = (fields: any[], rawValues: any[]) => {
    return fields.reduce((acc, field) => {
        const foundField = rawValues.find(
            (item: any) => item.field_id === field.field_id
        );
        acc[field.field_var] = foundField?.values?.[0]?.value || ""; // Если значение найдено, берём его, иначе пустая строка
        return acc;
    }, {});
};

const doDateConvert = (date: string) => {
    const dt = new Date(date);
    const year = dt.getFullYear();
    const month = (dt.getMonth() + 1).toString().padStart(2, "0"); // месяцы начинаются с 0
    const day = dt.getDate().toString().padStart(2, "0");
    const hours = dt.getHours().toString().padStart(2, "0");
    const minutes = dt.getMinutes().toString().padStart(2, "0");
    const seconds = dt.getSeconds().toString().padStart(2, "0");
    return `${day}.${month}.${year} ${hours}:${minutes}:${seconds}`;
};

const now = new Date();
const selectedDatepicker = ref<any>(new Date());

onMounted(() => {
    LeadStore.init(1);
});

const doSelectDatepicker = () => {
    const startDate = new Date();
    const endDate = new Date(new Date().setDate(startDate.getDate() + 7));
    selectedDatepicker.value = [startDate, endDate];
};

watch(selectedDatepicker, (startDate, endDate) => {
    filteringDate.value.dateStart = startDate;
    filteringDate.value.dateEnd = endDate;
    filteringDate.value.isFiltering = true;
});

// let tableDataArray = ref(leadComputed);
const filteringBy = (filter: string) => {
    switch (filter) {
        case "now":
            filteringDate.value.dateStart = new Date();
            filteringDate.value.dateEnd = new Date();
            filteringDate.value.isFiltering = true;
            break;
        case "yesterday":
            filteringDate.value.dateStart = yesterdayDay();
            filteringDate.value.dateEnd = new Date();
            filteringDate.value.isFiltering = true;
            break;
        case "week":
            filteringDate.value.dateStart = sevenDayAgo();
            filteringDate.value.dateEnd = new Date();
            filteringDate.value.isFiltering = true;
            break;
        case "mounth":
            filteringDate.value.dateStart = oneMonthAgo();
            filteringDate.value.dateEnd = new Date();
            filteringDate.value.isFiltering = true;
            break;
        case "thremounth":
            filteringDate.value.dateStart = threeMonthAgo();
            filteringDate.value.dateEnd = new Date();
            filteringDate.value.isFiltering = true;
            break;
        case "year":
            filteringDate.value.dateStart = oneYearsAgo();
            filteringDate.value.dateEnd = new Date();
            filteringDate.value.isFiltering = true;
            break;
        case "clear":
            filteringDate.value.dateStart = fiveYearsAgo();
            filteringDate.value.dateEnd = new Date();
            filteringDate.value.isFiltering = false;
            break;
        default:
            filteringDate.value.isFiltering = false;
            break;
    }
};
export interface filterObject {
    isFiltering: boolean;
    dateStart: Date;
    dateEnd: Date;
    utm?: string;
    numberPhone?: string;
    status?: string;
}


//начальное значение 5 лет назад
const filteringDate = ref<filterObject>({
    isFiltering: false,
    dateStart: oneMonthAgo(),
    dateEnd: new Date(),
});
const formatToDate = (date: string) => {
    var pattern = /(\d{2})\.(\d{2})\.(\d{4})/;
    return new Date(date.replace(pattern, "$3-$2-$1"));
};
// const filteringDate = ref<false | Date>(false);
const filteredLeads = computed(() => {
    const dateStart = filteringDate.value.dateStart;
    const dateEnd = filteringDate.value.dateEnd;
    const tmp2 = filteringDate.value.isFiltering;

    if (tmp2 === true) {
        if (dateStart && dateEnd) {
            if (filterUtm.value != "") {
                // lead.date >= dateStart.toLocaleDateString() &&
                // lead.date <= dateEnd.toLocaleDateString() &&
                return tableDataArray.value?.filter(
                    (lead: any) => lead.utmMedium == filterUtm.value
                );
            } else if (filterStatus.value != "") {
                return tableDataArray.value?.filter(
                    (lead: any) =>
                        lead.date >= dateStart.toLocaleDateString() &&
                        lead.date <= dateEnd.toLocaleDateString() &&
                        lead.status == filterStatus.value
                );
            } else if (filterPhone.value != "") {
                return tableDataArray.value?.filter(
                    (lead: any) =>
                        lead.date >= dateStart.toLocaleDateString() &&
                        lead.date <= dateEnd.toLocaleDateString() &&
                        lead.phone == filterPhone.value
                );
            } else {
                return tableDataArray.value?.filter(
                    (lead: any) =>
                        lead.date >= dateStart.toLocaleDateString() &&
                        lead.date <= dateEnd.toLocaleDateString()
                );
            }
        } else {
            return tableDataArray.value?.filter(
                (lead: any) =>
                    formatToDate(lead.date) >= dateStart &&
                    formatToDate(lead.date) <= dateEnd
            );
        }
    } else {
        return tableDataArray.value;
    }
});

const filterParametrDate = ref(false);
const filterParametrDateBtn = ref(false);
const filterParametrDateCalendar = ref(false);

const clearFilterDate = () => {
    filterParametrDateBtn.value = false;
    filteringDate.value.isFiltering = false;
};

const clearDatepicker = () => {
    filterParametrDateCalendar.value = false;
    filteringDate.value.isFiltering = false;
    filteringBy("clear");
};

const pushParametrFilter = (
    filteringDate: filterObject,
    param: string,
    value: any
) => {
    const newFilteringDate: filterObject = { ...filteringDate }; // Создаем копию исходного объекта
    switch (param) {
        case "dateStart":
            filteringDate.dateStart = value as Date;
            break;
        case "dateEnd":
            filteringDate.dateEnd = value as Date;
            break;
        case "utm":
            filteringDate.isFiltering = true;
            filteringDate.utm = value as string;
            break;
        case "numberPhone":
            filteringDate.numberPhone = value as string;
            break;
        case "status":
            filteringDate.status = value as string;
            break;
        default:
            break;
    }
    return filteringDate;
};

// filter utm

const filterUtm = ref("");
const filterPhone = ref("");
const filterStatus = ref("");
const filterAnother = (typeFilter: string) => {
    pushParametrFilter(filteringDate.value, typeFilter, filterUtm.value);
};

//filter utm end

const titleTable = ref([
    "Дата",
    "Этап",
    "Проект",
    "Название",
    "Статус 1",
    "Информация 4",
    "Информация 5",
    "Информация 2",
    "Информация 3",
    "Причина 1",
    "UTM_SOURCE",
    "UTM_MEDIUM",
    "UTM_CAMPAIGN",
    "UTM_CONTENT",
    "UTM_TERM",
    "ROISTAT",
    "Тип",
    "Метраж",
    "Информация 1",
]);

var state = {
    date: new Date(2016, 9, 16),
};
const formatter = {
    date: "DD MMM YYYY",
    month: "MMM",
};


const getFormattedDate = (date: any) => {
    const day = String(date.getDate()).padStart(2, "0");
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const year = date.getFullYear();

    return `${day}.${month}.${year}`;
};
//watch отслеживаем значение вчера 7 дней ,

const onClickHandler = (page: number) => {
    console.log(page);
};


</script>
<style>
.pagination {
    justify-content: space-between;
}
.pagination label {
    align-items: center;
}
.pagination-container {
    display: flex;
    column-gap: 10px;
}
.paginate-buttons {
    height: 40px;
    width: 40px;
    border-radius: 20px;
    cursor: pointer;
    background-color: rgb(242, 242, 242);
    border: 1px solid rgb(217, 217, 217);
    color: black;
}
.paginate-buttons:hover {
    background-color: #d8d8d8;
}
.active-page {
    background-color: #3498db;
    border: 1px solid #3498db;
    color: white;
}
.active-page:hover {
    background-color: #2988c8;
}

.section-pd {
    padding: 30px 0 80px;
    min-height: 80vh;
}
th {
    height: 43px;
    padding: 0 10px;
    color: #8095b2;
    background: #fbfafb;
    font-size: 10px;
    font-weight: 400;
    border-right: 1px solid #e1e1e1;
    /* width: 73px; */
    text-transform: uppercase;
}
th:last-child {
    border-right: 1px solid transparent;
}
.input-item {
    border: 1px solid rgb(229, 231, 235);
}
.group-btn {
    border: 1px solid #f5f5f5;
    border-radius: 8px;
    height: 30px;
    overflow: hidden;
}
.group-btn button {
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 10px;
    padding: 0 9px;
    height: 100%;
    border-right: 1px solid #f5f5f5;
}
.group-btn button:last-child {
    border-right: none;
}
.group-btn button:first-child {
    border-top-left-radius: 8px;
    border-bottom-left-radius: 8px;
}
.group-btn button:last-child {
    border-top-right-radius: 8px;
    border-bottom-right-radius: 8px;
}
.input-slot-image {
    height: 20px;
    width: auto;
    margin-left: 5px;
}
.dp__input {
    padding: 5px 10px;
    height: 30px;
}
.panel-first {
    margin: 0px 0 12px 0;
}
.panel-second {
    margin: 0px 0 45px 0;
}
.search {
    width: 300px;
    height: 47px;
}
.dp__theme_light {
    /*General*/

    --dp-border-radius: 0px; /*Configurable border-radius*/
    --dp-cell-border-radius: 0px; /*Specific border radius for the calendar cell*/
    --dp-common-transition: all 0.1s ease-in; /*Generic transition applied on buttons and calendar cells*/

    /*Sizing*/
    --dp-button-height: 35px; /*Size for buttons in overlays*/
    --dp-month-year-row-height: 35px; /*Height of the month-year select row*/
    --dp-month-year-row-button-size: 35px; /*Specific height for the next/previous buttons*/
    --dp-button-icon-height: 20px; /*Icon sizing in buttons*/
    --dp-cell-size: 35px; /*Width and height of calendar cell*/
    --dp-cell-padding: 5px; /*Padding in the cell*/
    --dp-common-padding: 10px; /*Common padding used*/
    --dp-input-icon-padding: 35px; /*Padding on the left side of the input if icon is present*/
    --dp-input-padding: 5px 30px 5px 30px; /*Padding in the input*/
    --dp-menu-min-width: 300px; /*Adjust the min width of the menu*/
    --dp-action-buttons-padding: 2px 5px; /*Adjust padding for the action buttons in action row*/
    --dp-row-margin: 5px 0; /*Adjust the spacing between rows in the calendar*/
    --dp-calendar-header-cell-padding: 0.5rem; /*Adjust padding in calendar header cells*/
    --dp-two-calendars-spacing: 10px; /*Space between multiple calendars*/
    --dp-overlay-col-padding: 3px; /*Padding in the overlay column*/
    --dp-time-inc-dec-button-size: 32px; /*Sizing for arrow buttons in the time picker*/
    --dp-menu-padding: 6px 8px; /*Menu padding*/

    /*Font sizes*/
    --dp-font-size: 12px; /*Default font-size*/
    --dp-preview-font-size: 0.8rem; /*Font size of the date preview in the action row*/
    --dp-time-font-size: 0.8rem; /*Font size in the time picker*/

    /*Transitions*/
    --dp-animation-duration: 0.1s; /*Transition duration*/
    --dp-menu-appear-transition-timing: cubic-bezier(
        0.4,
        0,
        1,
        1
    ); /*Timing on menu appear animation*/
    --dp-transition-timing: ease-out; /*Timing on slide animations*/
}

.datepicker-wrp {
    width: 233px;
    font-size: 10px;
}
.datepicker-wrp input {
    padding-left: 32px;
    border: 1px solid #f5f5f5;
    border-radius: 8px;
}
.dp__input_wrap {
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #f5f5f5;
}
.input-list,
.input-text {
    height: 30px;
    border-radius: 8px;
    max-width: 303px;
}
.input-search {
    height: 100%;
    border-radius: 8px 0 0 8px;
}
.input-search:focus {
    /* border-color: rgb(241 241 241 / var(--tw-border-opacity));
    --tw-bg-opacity: 1;
    background-color: rgb(255 255 255 / var(--tw-bg-opacity)); */
    box-shadow: none;
}
.input-text button {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    padding: 0 30px;
    border-radius: 0px 8px 8px 0;
}
.input-list button {
    height: 100%;
    border-radius: 8px;
}
table thead {
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
    /* overflow: hidden; */
    /* border: 1px solid #f1f1f1; */
}
table thead th:first-child {
    border-top-left-radius: 8px;
    overflow: hidden;
    min-width: 80px;
}
table thead th:nth-child(2) {
    min-width: 112px;
}
table thead th:last-child {
    border-top-right-radius: 8px;
    overflow: hidden;
}
table tbody tr:last-child > td:last-child {
    border-bottom-left-radius: 8px;
    overflow: hidden;
    /* border: 1px solid #f1f1f1; */
}

table {
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #f1f1f1;
    display: inline-block;
    height: 63vh;
    overflow-y: auto;
}
table tr {
    /* border-left: 1px solid #f1f1f1; */
}
tr:nth-child(2n) {
    background: #fbfafb;
}
td {
    height: 25px;
    padding-left: 9px;
    font-size: 10px;
}
table tr:last-child {
    border-bottom: none !important;
}
.tag-yellow {
    background: #fff9c6;
    padding: 1px 3px;
    border-radius: 3px;
}
.tag-blue {
    background-color: #c3dcfc;
    padding: 1px 3px;
    border-radius: 3px;
}
.tag-gray {
    background: #d9d9d9;
    padding: 1px 3px;
    border-radius: 3px;
}
.tag-red {
    background: #fcc3c3;
    padding: 1px 3px;
    border-radius: 3px;
}
.stage {
    color: rgba(0, 0, 0, 0.6);
    padding: 1px 3px;
    border-radius: 3px;
}
.name-leads,
.blue-text {
    color: #4097ff;
}
.pagination label {
    font-weight: 400;
    font-size: 10px;
    color: rgba(71, 71, 102, 0.75);
}
.result {
    display: flex;
    align-items: center;
    position: relative;
    border: 1px solid #f1f1f1;
    padding: 0 9px 0 30px;
    border-radius: 8px;
    width: 91px;
    height: 30px;
    font-size: 10px;
    color: #000;
}
.result::before {
    content: "";
    position: absolute;
    width: 15px;
    height: 16px;
    display: inline-block;
    left: 10px;
    top: 0;
    bottom: 0;
    margin: auto;
    background-image: url("data:image/svg+xml,%3Csvg width='15' height='16' viewBox='0 0 15 16' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' clip-rule='evenodd' d='M7.5 1.50015C6.3947 1.50015 5.48107 1.59749 4.77786 1.716L4.69684 1.72991C4.08614 1.8321 3.57823 1.91735 3.18098 2.4059C2.92702 2.71971 2.84419 3.05892 2.82544 3.43682L2.52795 3.53598C2.248 3.6291 2.00191 3.71133 1.80781 3.80203C1.5974 3.89998 1.40451 4.02575 1.25698 4.23072C1.10944 4.43509 1.05079 4.6582 1.02419 4.88857C1 5.10201 1 5.3602 1 5.65587V5.74354C1 5.98661 1 6.20126 1.01814 6.38084C1.03749 6.57493 1.08042 6.76418 1.18744 6.94678C1.29567 7.12999 1.43958 7.25938 1.60042 7.37064C1.74856 7.47343 1.936 7.57803 2.14884 7.69594L3.74512 8.58295C4.07163 9.22448 4.51967 9.79647 5.13581 10.2094C5.67214 10.5698 6.31609 10.7959 7.07856 10.8558C7.05735 10.9091 7.04648 10.9659 7.04651 11.0233V12.0814H6.18186C5.93723 12.0814 5.70016 12.1662 5.51098 12.3213C5.32181 12.4764 5.19223 12.6923 5.14428 12.9322L5.01186 13.593H3.87209C3.75182 13.593 3.63647 13.6408 3.55143 13.7259C3.46638 13.8109 3.4186 13.9262 3.4186 14.0465C3.4186 14.1668 3.46638 14.2821 3.55143 14.3672C3.63647 14.4522 3.75182 14.5 3.87209 14.5H11.1279C11.2482 14.5 11.3635 14.4522 11.4486 14.3672C11.5336 14.2821 11.5814 14.1668 11.5814 14.0465C11.5814 13.9262 11.5336 13.8109 11.4486 13.7259C11.3635 13.6408 11.2482 13.593 11.1279 13.593H9.98814L9.85572 12.9322C9.80777 12.6923 9.67819 12.4764 9.48902 12.3213C9.29984 12.1662 9.06277 12.0814 8.81814 12.0814H7.95349V11.0233C7.95352 10.9659 7.94265 10.9091 7.92144 10.8558C8.68391 10.7953 9.32786 10.5698 9.86419 10.21C10.4809 9.79647 10.9284 9.22448 11.2549 8.58295L12.8512 7.69594C13.064 7.57803 13.2514 7.47343 13.3996 7.37064C13.5598 7.25938 13.7043 7.12999 13.812 6.94739C13.9196 6.76418 13.9631 6.57493 13.9819 6.38084C14 6.20126 14 5.98661 14 5.74354V5.65587C14 5.3608 14 5.10201 13.9758 4.88857C13.9492 4.6582 13.8912 4.43509 13.743 4.23072C13.5955 4.02575 13.4026 3.89998 13.1928 3.80142C12.9975 3.71073 12.752 3.6291 12.472 3.53598L12.1746 3.43682C12.1564 3.05831 12.0736 2.71971 11.819 2.4059C11.4224 1.91675 10.9145 1.83149 10.3038 1.72991L10.2221 1.716C9.32234 1.5685 8.4118 1.4963 7.5 1.50015ZM9.06302 13.593L8.96628 13.1099C8.95943 13.0757 8.94093 13.0448 8.91393 13.0227C8.88692 13.0005 8.85307 12.9884 8.81814 12.9884H6.18186C6.14693 12.9884 6.11308 13.0005 6.08607 13.0227C6.05907 13.0448 6.04057 13.0757 6.03372 13.1099L5.93698 13.593H9.06302ZM2.83693 4.38914L2.8454 4.38611C2.88893 5.30517 2.99233 6.32098 3.25112 7.27027L2.60656 6.91292C2.37135 6.78171 2.22381 6.69948 2.1174 6.62572C2.01944 6.55739 1.9874 6.51688 1.96986 6.48665C1.95172 6.45642 1.93237 6.40925 1.92028 6.29074C1.90746 6.10198 1.90302 5.91274 1.90698 5.72359V5.67945C1.90698 5.35354 1.90758 5.14736 1.92512 4.99136C1.94205 4.84746 1.96865 4.79364 1.99284 4.76099C2.01642 4.72774 2.05874 4.68541 2.18995 4.62434C2.33265 4.55783 2.52856 4.49253 2.83693 4.38914ZM12.1546 4.38551C12.1117 5.30457 12.0077 6.32037 11.7495 7.26966L12.3934 6.91232C12.6287 6.78111 12.7762 6.69888 12.8826 6.62511C12.9806 6.55679 13.0126 6.51628 13.0301 6.48604C13.0483 6.45581 13.0676 6.40865 13.0797 6.29014C13.0924 6.16074 13.093 5.99205 13.093 5.72298V5.67884C13.093 5.35294 13.0924 5.14676 13.0749 4.99076C13.058 4.84685 13.0313 4.79304 13.0072 4.76039C12.9836 4.72713 12.9413 4.68481 12.81 4.62374C12.6673 4.55723 12.4714 4.49132 12.1631 4.38793L12.1546 4.38551ZM4.92902 2.61027C5.77888 2.47123 6.63885 2.40327 7.5 2.40711C8.55209 2.40711 9.41493 2.49962 10.071 2.61027C10.8008 2.73362 10.9538 2.77957 11.1152 2.9779C11.2736 3.17259 11.2887 3.35761 11.2561 4.17691C11.2017 5.54219 11.0215 7.01632 10.464 8.13733C10.1883 8.68997 9.82791 9.14225 9.3593 9.45666C8.89312 9.76926 8.29149 9.96517 7.5 9.96517C6.70851 9.96517 6.10749 9.76926 5.6413 9.45666C5.17209 9.14225 4.81172 8.68997 4.5366 8.13672C3.97851 7.01632 3.79893 5.5428 3.74451 4.1763C3.71186 3.35761 3.72637 3.17259 3.8854 2.9779C4.04623 2.77957 4.19921 2.73362 4.92902 2.61027Z' fill='%234097FF' /%3E%3C/svg%3E");
}
.input-wrp {
    max-width: 303px;
}
@media (max-width: 768px) {
    .group-btn button {
        font-size: 9px;
        padding: 0 5px;
    }
    .input-wrp,
    .group-btn {
        margin-bottom: 10px;
    }
    .profile-wrp {
        width: 50%;
        max-width: 120px;
    }
}
.paginate-info{
    display: flex;
    gap: 14px;
    height: 30px;
}
.paginate-info > div {
    display: flex;
    align-items: center;
}
.paginate-info .btn-more {
    cursor: pointer;
    display: flex;
    align-items: center;
    position: relative;
    border: 1px solid #f1f1f1;
    padding: 0 9px;
    border-radius: 8px;
    height: 30px;
    font-size: 10px;
    color: #000;
}
</style>
