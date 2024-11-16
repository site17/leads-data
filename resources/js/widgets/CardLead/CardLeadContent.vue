<template>
    <section class="card-content">
        <div class="container mx-auto">
            <div>
                <div class="link-back" @click="$router.push('/leads')">
                    Список 
                </div>
            </div>

            <div class="top-column md:flex lg:flex">
                <div class="title" v-if="ContactData?.first_name!=''">{{ ContactData?.first_name }}</div>
                <div class="title" v-else >{{ ContactData?.name }}</div>
                <div class="tags-wrapper">
                    <div class="flex items-center">
                        <div
                            class="item-tag color-yellow"
                            :style="{
                                background: tableDataArray?.leadStatusColor,
                            }"
                        >
                            {{ tableDataArray?.leadStatus }}
                        </div>
                        <div class="item-tag">
                            {{ tableDataArray?.form2 }}
                        </div>
                        <div class="item-tag">
                            {{ tableDataArray?.project }}
                        </div>
                        <div class="item-tag"></div>
                        <div class="item-tag item-id link-icon">
                            <a
                                :href="`https://project.amocrm.ru/leads/detail/${LeadData?.lead_id}`"
                                >#{{ LeadData?.lead_id }}</a
                            >
                        </div>
                    </div>
                </div>
            </div>
            <div class="">
                <div class="panel-tabs flex">
                    <div
                        @click="selectedTab(0)"
                        class="item-tab-link"
                        :class="activeTab == 0 ? 'active' : ''"
                    >
                        Основное
                    </div>
                    <div
                        @click="selectedTab(1)"
                        class="item-tab-link"
                        :class="activeTab == 1 ? 'active' : ''"
                    >
                        Информация 1
                    </div>
                    <div
                        @click="selectedTab(2)"
                        class="item-tab-link"
                        :class="activeTab == 2 ? 'active' : ''"
                    >
                        Информация 2
                    </div>
                    <div
                        @click="selectedTab(3)"
                        class="item-tab-link"
                        :class="activeTab == 3 ? 'active' : ''"
                    >
                        Рекламный канал
                    </div>
                </div>
            </div>

            <div class="md:flex lg:flex">
                <div class="md:max-md:w-2/6 lg:w-2/6">
                    <div class="column">
                        <div class="bottom-column">
                            <div>
                                <div v-if="activeTab == 0">
                                    <div class="table-wrp">
                                        <div class="table-item">
                                            <div class="table-column">
                                                Клиент
                                            </div>
                                            <div class="table-column">
                                                {{ ContactData?.first_name }}
                                            </div>
                                        </div>
                                        <div class="table-item">
                                            <div class="table-column">
                                                Адрес
                                            </div>
                                            <div class="table-column">
                                                <span
                                                    class="blue-text adres-text"
                                                    >{{
                                                        tableDataArray?.adresObj
                                                    }}</span
                                                >
                                            </div>
                                        </div>
                                        <div class="table-item">
                                            <div class="table-column">
                                                Моб.телефон
                                            </div>
                                            <div
                                                class="table-column link-icon-copy"
                                            >
                                                <span>
                                                    {{
                                                        getValueByFieldId(
                                                            250023
                                                        )
                                                    }}</span
                                                >
                                            </div>
                                        </div>
                                        <div class="table-item">
                                            <div class="table-column">
                                                Телеграм
                                            </div>
                                            <div
                                                class="table-column link-icon-copy"
                                            >
                                                <span>
                                                    {{
                                                        getValueByFieldId(
                                                            932497
                                                        )
                                                    }}
                                                    </span>
                                            </div>
                                        </div>
                                        <div class="table-item">
                                            <div class="table-column">
                                                Доп.информация
                                            </div>
                                            <div class="table-column">
                                                <span>
                                                    {{
                                                        getValueByFieldId(
                                                            250023
                                                        )
                                                    }}
                                                    </span>
                                            </div>
                                        </div>
                                        <div class="table-item">
                                            <div class="table-column">
                                                Отв-ный
                                            </div>
                                            <div class="table-column">
                                                {{ tableDataArray?.otv }}
                                            </div>
                                        </div>
                                        <div class="table-item">
                                            <div class="table-column">
                                                Информация 4
                                            </div>
                                            <div class="table-column">
                                                {{ tableDataArray?.bdg }}
                                            </div>
                                        </div>
                                        <div class="table-item">
                                            <div class="table-column">
                                                Причина 2
                                            </div>
                                            <div class="table-column">
                                                {{
                                                    tableDataArray?.prichinatype2
                                                }}
                                            </div>
                                        </div>
                                        <div class="table-item">
                                            <div class="table-column">
                                                Причина 1
                                            </div>
                                            <div class="table-column">
                                                {{
                                                    tableDataArray?.prichinatype1
                                                }}
                                            </div>
                                        </div>
                                        <div class="table-item">
                                            <div class="table-column">
                                                Дата 1
                                            </div>
                                            <div class="table-column">
                                                {{ tableDataArray?.datetype1 }}
                                            </div>
                                        </div>

                                        <div class="table-item">
                                            <div class="table-column">
                                                Проект
                                            </div>
                                            <div class="table-column">
                                                {{ tableDataArray?.project }}
                                            </div>
                                        </div>
                                        <div class="table-item">
                                            <div class="table-column">
                                                Дизайн-проект
                                            </div>
                                            <div class="table-column">
                                                <span
                                                    v-if="
                                                        tableDataArray?.prtype1
                                                    "
                                                    >Да</span
                                                >
                                                <span v-else>Нет</span>
                                            </div>
                                        </div>
                                        <div class="table-item">
                                            <div class="table-column">
                                                Метраж (м2)
                                            </div>
                                            <div class="table-column">
                                                {{ tableDataArray?.metrag }}
                                            </div>
                                        </div>
                                        

                                    </div>

                                </div>
                                <div v-if="activeTab == 1">
                                    <div class="table-wrp">
                                        <div class="table-item">
                                            <div class="table-column">
                                                Встреча 1
                                            </div>
                                            <div class="table-column">
                                                <span class="red-tag">
                                                    {{
                                                        tableDataArray?.vstr1
                                                    }}</span
                                                >
                                            </div>
                                        </div>
                                        <div class="table-item">
                                            <div class="table-column">
                                                Встреча 2
                                            </div>
                                            <div class="table-column">
                                                <span class="blue-tag">
                                                    {{
                                                        tableDataArray?.vstr2
                                                    }}</span
                                                >
                                            </div>
                                        </div>
                                      
                                        <div class="table-item">
                                            <div class="table-column">
                                                Адрес объекта
                                            </div>
                                            <div class="table-column">
                                                {{ tableDataArray?.adresObj }}
                                            </div>
                                        </div>
                                        <div class="table-item">
                                            <div class="table-column">ЖК</div>
                                            <div class="table-column">
                                                {{ tableDataArray?.gk }}
                                            </div>
                                        </div>
                                        <div class="table-item">
                                            <div class="table-column">
                                                Специалист 1
                                            </div>
                                            <div class="table-column">
                                                {{
                                                    tableDataArray?.spec1
                                                }}
                                            </div>
                                        </div>
                                        <div class="table-item">
                                            <div class="table-column">
                                                Специалист 2
                                            </div>
                                            <div class="table-column">
                                                {{ tableDataArray?.spec2 }}
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div v-if="activeTab == 2">
                                    <div class="table-wrp">
                                        <div class="table-item">
                                            <div class="table-column">
                                                Информация 3
                                            </div>
                                            <div class="table-column">
                                                {{ tableDataArray?.inf3 }}
                                            </div>
                                        </div>
                                      
                                        <div class="table-item">
                                            <div class="table-column">Информация 1</div>
                                            <div class="table-column">
                                                {{ tableDataArray?.inf1 }}
                                            </div>
                                        </div>
                                        <div class="table-item">
                                            <div class="table-column">
                                                Информация 2
                                            </div>
                                            <div class="table-column">
                                                {{ tableDataArray?.inf2 }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="activeTab == 3">
                                    <div class="table-wrp">
                                        <div class="table-item">
                                            <div class="table-column">
                                                Форма 1
                                            </div>
                                            <div class="table-column">
                                                {{ tableDataArray?.form1 }}
                                            </div>
                                        </div>
                                        <div class="table-item">
                                            <div class="table-column">
                                                Форма 2
                                            </div>
                                            <div class="table-column">
                                                {{
                                                    tableDataArray?.form2
                                                }}
                                            </div>
                                        </div>
                                        <div class="table-item">
                                            <div class="table-column">
                                                IP Адрес
                                            </div>
                                            <div class="table-column">
                                                {{ tableDataArray?.ipAdres }}
                                            </div>
                                        </div>
                                        <div class="table-item">
                                            <div class="table-column">
                                                ROISTAT
                                            </div>
                                            <div class="table-column">
                                                {{ tableDataArray?.roistat }}
                                            </div>
                                        </div>
                                        <div class="table-item">
                                            <div class="table-column">
                                                UTM_SOURCE
                                            </div>
                                            <div class="table-column">
                                                {{ tableDataArray?.utmSource }}
                                            </div>
                                        </div>
                                        <div class="table-item">
                                            <div class="table-column">
                                                UTM_MEDIUM
                                            </div>
                                            <div class="table-column">
                                                {{ tableDataArray?.utmMedium }}
                                            </div>
                                        </div>
                                        <div class="table-item">
                                            <div class="table-column">
                                                UTM_CAMPAIGN
                                            </div>
                                            <div class="table-column">
                                                {{
                                                    tableDataArray?.utmCompaign
                                                }}
                                            </div>
                                        </div>
                                        <div class="table-item">
                                            <div class="table-column">
                                                UTM_CONTENT
                                            </div>
                                            <div class="table-column">
                                                {{ tableDataArray?.utmContent }}
                                            </div>
                                        </div>
                                        <div class="table-item">
                                            <div class="table-column">
                                                UTM_TERM
                                            </div>
                                            <div class="table-column">
                                                {{ tableDataArray?.utmTerm }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md:max-md:w-4/6 lg:w-4/6">
                    <div class="content-card">
                        <div class="event-date">Сегодня</div>
                       
                       
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section></section>
</template>
<script setup lang="ts">
import { computed, onMounted, ref, watch } from "vue";
import { defineComponent } from "vue";
import { FwbTab, FwbTabs } from "flowbite-vue";
import { useLeadStore } from "@/entities/leads/store";
import { storeToRefs } from "pinia";
import { useRoute } from "vue-router";
import { Leads } from "@/entities/leads/types";
import { LeadsModel } from "@/entities/leads/model";
import { customFieldsObj } from "../../shared/composables/helper";
const { leads, selectedLeadId, statuses, leadFields, leadIds } = storeToRefs(
    useLeadStore()
);
const LeadStore = useLeadStore();
const activeTab = ref(0);
const selectedTab = (index: number) => {
    activeTab.value = index;
};
const route = useRoute();
const leadId = computed(() => route.params.id);
const tableData = computed(() => {
    return leads.value?.find((lead: Leads) => {
        return lead.id === Number(leadId.value);
    });
});
interface ContactField {
    field_code: string;
    field_id: number;
    field_name: string;
    value: string;
}
const LeadData = ref<Leads>();
const FieldsData = ref();
const ContactData = ref();
const ContactFields = ref<Array<ContactField>>();
onMounted(async () => {

    try {
       
        const resultLead = await LeadsModel.getLead(Number(leadId.value));
        LeadData.value = resultLead;
    } catch (error) {
        error = error;
    }

    try {
       
        const resultFields = await LeadsModel.getFieldsByIds([
            Number(leadId.value),
        ]);

        FieldsData.value = resultFields;
    } catch (error) {
        error = error;
    }

    try {
        
        const resultContact = await LeadsModel.getContctByIdLead(
            Number(leadId.value)
        );

        ContactData.value = resultContact;

        let contactCustomFieldsAll = JSON.parse(
            ContactData.value.custom_fields
        );
    
        const formattedFields = contactCustomFieldsAll.map((field: any) => {
            // Извлекаем первое значение из массива values (например, первое значение телефона)
            const firstValue =
                field.values && field.values.length > 0
                    ? field.values[0].value
                    : null;

            // Возвращаем новый объект с полем value
            return {
                field_code: field.field_code,
                field_id: field.field_id,
                field_name: field.field_name,
                value: firstValue, // Добавляем значение телефона (или других данных)
            };
        });
       
        ContactFields.value = formattedFields;
    } catch (error) {
        error = error;
    }

    try {
      
        const resultContact = await LeadsModel.getNoteByIdLead(
            Number(leadId.value)
        );
        console.log("resultNote", resultContact);
        ContactData.value = resultContact;
    } catch (error) {
        error = error;
    }
});
onMounted(() => {
    LeadStore.init(1);
});
const tableDataArray = computed(() => {
    if (LeadData.value && LeadData.value.raw_content) {
        let raw = JSON.parse(LeadData.value.raw_content);

        let extractedFields = extractCustomFields(
            customFieldsObj,
            raw.custom_fields_values || []
        );

        let status = statuses.value?.find((item: any) => {
            return item.status_id == LeadData.value?.status_id;
        });

        return {
            leadStatus: status?.name,
            leadStatusColor: status?.color,
            utmMedium: extractedFields["25443544"] || "...",
            utmSource: extractedFields["2544377"] || "...",
            utmContent: extractedFields["2544381"] || "...",
            utmCompaign: extractedFields["2544383"] || "...",
            utmTerm: extractedFields["2544387"] || "...",
            adresObj: extractedFields["270209"] || "...",
            otv: extractedFields["952623"] || "...",
            bdg: extractedFields["930543"] || "...",
            prichinatype2: extractedFields["259981"] || "...",
            prichinatype1: extractedFields["789725"] || "...",
            datetype1: extractedFields["929099"] || "...",
            project: extractedFields["268899"] || "...",
            prtype1: extractedFields["6616544"] || "...",
            metrag: extractedFields["935285"] || "...",
            mkad: extractedFields["914297"] || "...",
            gk: extractedFields["943155"] || "...",
            spec1: extractedFields["657753"] || "...",
            spec2: extractedFields["911847"] || "...",
            inf3: extractedFields["943907"] || "...",
            inf1: extractedFields["950365"] || "...",
            inf2: extractedFields["950423"] || "...",
            form1: extractedFields["268991"] || "...",
            form2: extractedFields["268925"] || "...",
            ipAdres: extractedFields["931671"] || "...",
            roistat: extractedFields["789941"] || "...",
            vstr1: extractedFields["789941"] || "...",
            vstr2: extractedFields["789941"] || "...",
        };
    }
});

const extractCustomFields = (fields: any[], rawValues: any[]) => {
    return fields.reduce((acc, field) => {
        const foundField = rawValues.find(
            (item: any) =>
                String(item.field_id) === String(field.custom_field_id)
        );

        acc[field.custom_field_id] = foundField?.values?.[0]?.value || ""; // Если значение найдено, берём его, иначе пустая строка

        return acc;
    }, {});
};
//получаем значение по field_id
function getValueByFieldId(fieldId: number) {
    const field = ContactFields.value?.find((f) => f.field_id === fieldId);

    if (field && field.value.length > 0) {
        return field.value; // Возвращаем первое значение
    }

    return null; // Если ничего не найдено или нет значений
}
</script>
<style scoped>
.top-column {
    padding: 25px 25px 45px 0;
}
.title {
    font-size: 20px;
    color: #242020;
    line-height: 1;
}
.color-gray {
    font-size: 11px;
    color: #8095b2;
}
.item-tag {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: row;
    border-radius: 3px;
    font-size: 11px;
    line-height: 1;
    padding: 3px;
    background: #4097ff4d;
    margin: 0 3px;
    height: 21px;
}
.tags-wrapper {
    color: #302c2c;
}
.status-lead {
    color: #ffffff;
    font-size: 12px;
    margin-right: 5px;
}
.border-line {
    position: relative;
    background: #8095b2;
    height: 3px;
    margin: 3px 0;
}
.border-line:before {
    content: "";
    position: absolute;
    width: 25%;
    left: 0;
    top: 0;
    display: block;
    height: 3px;
    background: #fffdce;
    z-index: 2;
}
.border-line::after {
    content: "";
    position: absolute;
    width: 50%;
    left: 0;
    top: 0;
    display: block;
    height: 3px;
    background: #bbdaff;
}
.note-time-label__wrapper {
    display: flex;
    justify-content: center;
    text-align: center;
    position: relative;
    margin: 15px 0;
}

.note-time-label__wrapper:before {
    content: "";
    position: absolute;
    left: -20px;
    right: -20px;
    bottom: 10px;
    height: 1px;
    background: #c5c5c5;
}

.note-time-label__wrapper:first-child {
    margin-top: 0;
}

.note-time-label__wrapper_load-more {
    padding-top: 46px;
}

.note-time-label__wrapper_load-more .feed__load_more {
    position: absolute;
    top: 0;
}

.note-time-label:before {
    content: "";
    position: absolute;
    top: 0;
    bottom: 0;
    left: -12px;
    right: -12px;
    background: #c5c5c5;
    border: 1px solid #c5c5c5;
    z-index: -1;
    border-radius: 10px;
}
.note-time-label__wrapper + .feed-note-wrapper:not(.feed-note-wrapper_system) {
    margin-top: 15px;
}

.note-time-label {
    color: #c5c5c5;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    position: relative;
    z-index: 0;
    padding: 1px 0;
}

.note-time-label__wrapper {
    display: flex;
    justify-content: center;
    text-align: center;
    position: relative;
    margin: 15px 0;
}

.note-time-label__wrapper:before {
    content: "";
    position: absolute;
    left: -20px;
    right: -20px;
    bottom: 10px;
    height: 1px;
    background: #c5c5c5;
}

.note-time-label__wrapper:first-child {
    margin-top: 0;
}

.note-time-label__wrapper_load-more {
    padding-top: 46px;
}

.note-time-label__wrapper_load-more .feed__load_more {
    position: absolute;
    top: 0;
}

.note-time-label:before {
    content: "";
    position: absolute;
    top: 0;
    bottom: 0;
    left: -12px;
    right: -12px;
    background: #ffffff;
    border: 1px solid #c5c5c5;
    z-index: -1;
    border-radius: 10px;
}

.color-yellow {
    background: #fff9c6;
    margin-left: 30px;
}
.item-id {
    color: #4097ff;
    background: transparent;
    margin-left: 24px;
}
.item-tab-link {
    cursor: pointer;
    font-size: 12px;
    height: 22px;
    padding: 0 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ced1d9;
    position: relative;
    margin-right: 10px;
}
.item-tab-link.active {
    color: #201f1f;
}
.panel-tabs {
    max-width: 550px;
}
.item-tab-link:after {
    content: "";
    position: absolute;
    bottom: 0;
    width: 100%;
    border-bottom: 1px solid transparent;
}
.item-tab-link.active:after {
    border-bottom: 1px solid #ced1d9;
}
.panel-tabs {
    margin-bottom: 10px;
}
.column {
    border: 1px solid #f1f1f1;
    padding: 30px;
    border-radius: 3px;
    height: 100vh;
    overflow-y: auto;
}
.table-item {
    display: flex;
    border-bottom: 1px solid #f1f1f1;
    padding: 8px 0;
}
.table-item .table-column {
    color: #000000;
    justify-content: space-between;
    width: 50%;
}
.table-item .table-column:first-child {
    color: rgba(0, 0, 0, 0.5);
    text-align: left;
}
.table-item .table-column:last-child {
    text-align: right;
}
.blue-text {
    color: #4097ff;
}
.red-tag {
    background-color: #fcc3c3;
    padding: 3px;
    border-radius: 3px;
}
.yellow-tag {
    background-color: #fffdce;
    padding: 3px;
    border-radius: 3px;
}
.blue-tag {
    background-color: #c3dcfc;
    padding: 3px;
    border-radius: 3px;
}
.content-card {
    background: #fbfafb;
    padding: 22px 30px;
    height: 100%;
}
.event-date {
    border-radius: 20px;
    padding: 0 4px;
    height: 17px;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    background: #ffffff;
    font-size: 11px;
    color: #666;
    margin: 15px 0;
}
.event-card {
    border: 1px solid #f1f1f1;
    border-radius: 8px;
    width: 100%;
    background: #fff;
    padding: 15px;
    margin-bottom: 7px;
}
.icon-column {
    width: 34px;
}
.about-column {
    width: 95%;
    padding-left: 11px;
}
.event-card-top {
    font-size: 11px;
    padding-top: 11px;
    margin-bottom: 9px;
}
.event-card-top > span {
    display: inline-block;
    margin-right: 10px;
    color: rgba(0, 0, 0, 0.6);
}
.icon {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    border: 1px solid #bcbcbc;
    display: flex;
    align-items: center;
    justify-content: center;
}
.gray-card-event {
    background: #ecedf0;
}
.status-color {
    font-weight: 400;
    font-size: 11px;
    color: rgba(0, 0, 0, 0.9);
    border-radius: 3px;
    padding: 0 5px;
    height: 17px;
    display: inline-flex;
    justify-content: center;
    align-items: center;
}
.status-blue {
    background: #c3dcfc;
    border: 1px solid #ababab;
}
.status-yellow {
    background: #fffdce;
    border: 1px solid #ababab;
}
.blue-text.adres-text {
    position: relative;
    padding-left: 15px;
    color: rgba(0, 0, 0, 0.6);
    /* cursor: pointer; */
}
.blue-text.adres-text::before {
    content: "";
    position: absolute;
    width: 12px;
    height: 13px;
    display: inline-block;
    left: 0;
    top: 0px;
    background-image: url("data:image/svg+xml,%3Csvg width='12' height='13' viewBox='0 0 12 13' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' clip-rule='evenodd' d='M6 3C4.896 3 4 3.896 4 5C4 6.104 4.896 7 6 7C7.104 7 8 6.104 8 5C8 3.896 7.104 3 6 3ZM4.8 5C4.8 4.336 5.336 3.8 6 3.8C6.664 3.8 7.2 4.336 7.2 5C7.2 5.664 6.664 6.2 6 6.2C5.336 6.2 4.8 5.664 4.8 5Z' fill='black' fill-opacity='0.6' /%3E%3Cpath fill-rule='evenodd' clip-rule='evenodd' d='M5.77455 11.9175C5.77455 11.9175 5.77455 11.9175 6 11.6071L5.77455 11.9175C5.90546 12.0275 6.09454 12.0275 6.22545 11.9175L6 11.6071C6.22545 11.9175 6.22545 11.9175 6.22545 11.9175V11.9136H6.22909L6.23637 11.9057C6.24728 11.8978 6.25818 11.89 6.26909 11.8743C6.29818 11.8507 6.33817 11.8154 6.39271 11.7682C6.49453 11.6739 6.64 11.5364 6.81454 11.3596C7.16363 11.01 7.63273 10.5071 8.09819 9.89428C9.02183 8.69214 10 6.9989 10 5.19961C10 2.86997 8.2 1 6 1C3.8 1 2 2.86997 2 5.19961C2 6.9989 2.97817 8.69214 3.90181 9.89428C4.36727 10.5071 4.83637 11.01 5.18546 11.3596C5.36 11.5364 5.50547 11.6739 5.60729 11.7682C5.66183 11.8154 5.70182 11.8507 5.73091 11.8743C5.74182 11.89 5.75272 11.8978 5.76363 11.9057L5.77091 11.9136H5.77455V11.9175ZM2.72727 5.19961C2.72727 3.32569 4.18182 1.78571 6 1.78571C7.81818 1.78571 9.27273 3.32569 9.27273 5.19961C9.27273 6.71211 8.43272 8.22463 7.53818 9.39534C7.09454 9.97284 6.65455 10.4521 6.32 10.7821C6.19637 10.90544 6.08727 11.0139 6 11.0964C5.91273 11.0139 5.80363 10.90544 5.68 10.7821C5.34545 10.4521 4.90546 9.97284 4.46183 9.39534C3.56728 8.22463 2.72727 6.71211 2.72727 5.19961Z' fill='black' fill-opacity='0.6' /%3E%3C/svg%3E");
}
.link-icon {
    position: relative;
    padding-right: 18px;
    cursor: pointer;
}
.link-icon::after {
    content: "";
    position: absolute;
    width: 16px;
    height: 15px;
    display: inline-block;
    right: 0;
    top: 0px;
    bottom: 0;
    margin: auto;
    background-image: url("data:image/svg+xml,%3Csvg width='16' height='15' viewBox='0 0 16 15' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 11H4V4H6.5V3H4C3.73478 3 3.48043 3.10536 3.29289 3.29289C3.10536 3.48043 3 3.73478 3 4V11C3 11.2652 3.10536 11.5196 3.29289 11.7071C3.48043 11.8946 3.73478 12 4 12H11C11.2652 12 11.5196 11.8946 11.7071 11.7071C11.8946 11.5196 12 11.2652 12 11V8.5H11V11Z' fill='%234097FF' /%3E%3Cpath d='M8.15385 3L9.74038 4.58654L7 7.32692L7.67308 8L10.4135 5.25962L12 6.84615V3H8.15385Z' fill='%234097FF' /%3E%3C/svg%3E");
}
.link-icon-copy {
    position: relative;
    padding-right: 18px;
    cursor: pointer;
}
.link-icon-copy::after {
    content: "";
    position: absolute;
    width: 16px;
    height: 16px;
    display: inline-block;
    right: 0;
    top: 0px;
    bottom: 0;
    margin: auto;
    background-image: url("data:image/svg+xml,%3Csvg width='16' height='16' viewBox='0 0 16 16' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M10 4H5V10' stroke='%234097FF' stroke-linecap='round' stroke-linejoin='round' /%3E%3Cpath d='M7 6H12V11.1429C12 11.3702 11.9122 11.5882 11.7559 11.7489C11.5996 11.9097 11.3877 12 11.1667 12H7.83333C7.61232 12 7.40036 11.9097 7.24408 11.7489C7.0878 11.5882 7 11.3702 7 11.1429V6Z' stroke='%234097FF' stroke-linecap='round' stroke-linejoin='round' /%3E%3C/svg%3E");
}
.link-back {
    position: relative;
    padding-left: 10px;
    cursor: pointer;
    font-size: 10px;
    color: rgba(32, 31, 31, 0.5);
    margin: 25px 0 0;
}
.link-back::before {
    content: "";
    position: absolute;
    width: 8px;
    height: 7px;
    display: inline-block;
    left: 0;
    top: 0px;
    bottom: 0;
    margin: auto;
    background-image: url("data:image/svg+xml,%3Csvg width='8' height='7' viewBox='0 0 8 7' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 3.5L3.57143 1M1 3.5L3.57143 6M1 3.5L7 3.5' stroke='%23201F1F' stroke-opacity='0.5' stroke-linecap='round' stroke-linejoin='round' /%3E%3C/svg%3E");
}
.card-content {
    padding-bottom: 60px;
}
.add-info {
    margin-top: 35px;
    border-bottom: 1px solid #f1f1f1;
    padding-bottom: 15px;
}
.add-info .list-title {
    font-size: 12px;
    color: rgba(0, 0, 0, 0.6);
    margin-bottom: 10px;
}
.add-info ul {
    list-style: none;
    padding-left: 0;
}
.add-info ul li,
input[type="checkbox"] + label {
    position: relative;
    padding-left: 15px;
    font-size: 12px;
    color: #111;
}

.add-info ul li:before {
    content: "";
    position: absolute;
    border-radius: 2px;
    width: 10px;
    height: 10px;
    display: inline-block;
    left: 0;
    top: 0px;
    bottom: 0;
    margin: auto;
    background: #4097ff;
}
input[type="checkbox"]:not(checked) + label {
    position: relative;
    padding: 0 0 0 25px;
}
input[type="checkbox"]:checked + label:before {
    content: "";
    position: absolute;
    top: 5px;
    left: 5px;
    width: 10px;
    height: 10px;
    border: 1px solid #d9d9d9;
    border-radius: 2px;
    opacity: 1;
}
input[type="checkbox"]:not(checked) + label:before {
    content: "";
    position: absolute;
    top: 3px;
    left: 5px;
    width: 10px;
    height: 10px;
    border: 1px solid #d9d9d9;
    border-radius: 2px;
    opacity: 1;
}
input[type="checkbox"]:not(checked) + label:after {
    content: "";
    position: absolute;
    top: 3px;
    left: 5px;
    width: 13px;
    height: 12px;
    border-radius: 0;
    background-image: url("data:image/svg+xml,%3Csvg width='13' height='12' viewBox='0 0 13 12' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 5.66544L4.34327 9.5L11.5073 1.2832' stroke='%234097FF' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round' /%3E%3C/svg%3E");
    opacity: 0;
    -webkit-transition: all 0.2s;
    -o-transition: all 0.2s;
    transition: all 0.2s;
}
input[type="checkbox"]:checked + label:after {
    opacity: 1;
}
input[type="checkbox"] {
    position: absolute;
    top: 0;
    left: 0;
    opacity: 0;
}
.event-body1 {
    display: flex;
    align-items: baseline;
}
.link-listen {
    cursor: pointer;
    border: 0.5px solid #4097ff;
    border-radius: 3px;
    padding: 0 5px;
    color: #4097ff;
    height: 15px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin: 0 5px;
}
.download-link {
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-bottom: 0.5px solid #4097ff;
    padding: 0 0 2px 0;
    height: 15px;
    color: #4097ff;
}
@media (max-width: 768px) {
    .item-tag.color-yellow {
        margin-left: 0;
    }
    .tags-wrapper {
        margin: 20px 0;
    }
    .top-column {
        padding: 15px 0;
    }
    .item-tag {
        font-size: 9px;
    }
    .item-tab-link {
        font-size: 10px;
        margin-right: 8px;
    }
    .column {
        padding: 15px;
    }
    .content-card {
        padding: 15px;
        height: auto;
    }
    .icon {
        width: 20px;
        height: 20px;
    }
    .icon svg {
        max-width: 100%;
    }
    .icon-column {
        width: 22px;
    }
    .event-card-top {
        font-size: 11px;
        margin-bottom: 9px;
        padding-top: 0;
    }
    .add-info,
    .bottom-column,
    .content-card {
        font-size: 11px;
    }
    .add-info {
        margin-top: 15px;
        padding-bottom: 15px;
    }
}
</style>
