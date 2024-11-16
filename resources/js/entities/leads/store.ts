import { defineStore } from "pinia";
import { LeadField, Leads, Statuses } from "./types";
import { LeadsModel } from "./model";
export const useLeadStore = defineStore("leads", {
    state: () => ({
        leads: undefined as undefined | Array<Leads>,
        selectedLeadId: undefined as undefined | number,
        loading: false as boolean,
        error: null as any,
        statuses: undefined as undefined | Array<Statuses>,
        leadFields: undefined as undefined | Array<LeadField>,
        leadIds: [] as Array<number> | Array<number>,
        leadTotalPages:undefined as undefined|number,
        leadCurrentPage:undefined as undefined|number,
    }),
    getters: {
        getAllStat: (state) => {
            console.log("state.leads", state.leads);
            return state.leads;
        },
    },
    actions: {
        async fetchLead(id_user: number) {
            this.leads = undefined;
            this.leadTotalPages = undefined;
            this.leadCurrentPage = undefined;
            this.loading = true;
            try {
                const result = await LeadsModel.getAllLeads();
                this.leads = result.data;
                this.leadTotalPages = result.total;
                this.leadCurrentPage = result.current_page;
               
            } catch (error) {
                this.error = error;
            } finally {
                this.loading = false;
            }
        },
        async fetchStatuses() {
            this.statuses = undefined;
            this.loading = true;
            try {
                const result = await LeadsModel.getStatuses();
                this.statuses = result;
            } catch (error) {
                this.error = error;
            } finally {
                this.loading = false;
            }
        },
        async fetchFields(lead_id: number) {
            this.leadFields = undefined;
            this.loading = true;
            try {
                if (this.leadIds.length > 0) {
                    // Получаем поля по лид-идентификаторам
                    const resultFields = await LeadsModel.getFieldsByIds([
                        lead_id,
                    ]);
                    this.leadFields = resultFields;
                    console.log("this.leadFields", this.leadFields);
                }
            } catch (error) {
                this.error = error;
            } finally {
                this.loading = false;
            }
        },

        setSelectedLeadId(id: number): boolean {
           
            this.selectedLeadId = id;
            return true;
        },
        async init(id_user: number): Promise<boolean> {
            try {
                await Promise.all([this.fetchLead(1), this.fetchStatuses()]);
                console.log("init1");
                return true;
            } catch (error) {
                return false;
            }
        },
    },
});
