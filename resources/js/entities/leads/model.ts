import {
    Contact,
    LeadField,
    Leads,
    LeadsData,
    Status,
    Statuses,
} from "./types";
// import { LeadsAll } from "./mock.data";
import { ApiLeads } from "@/shared/api/modules";
export class LeadsModel {

    static async getAllLeads(): Promise<LeadsData> {
        const result = await ApiLeads.getInstance().getAllLeads();
        return result;
    }
    static async getLead(lead_id: number): Promise<Leads> {
        const result = await ApiLeads.getInstance().getLead(lead_id);
        return result;
    }
    static async getLeadById(id: number): Promise<Leads> {
        const result = await ApiLeads.getInstance().getLeadById(id);
        return result;
    }
    static async getStatus(status_id: number): Promise<Status> {
        const result = await ApiLeads.getInstance().getStatusById(status_id);
        return result;
    }

    static async getStatuses(): Promise<Array<Statuses>> {
        const result = await ApiLeads.getInstance().getStatuses();
        return result;
    }
    static async getFieldsByIds(
        lead_ids: Array<number>
    ): Promise<Array<LeadField>> {
        const result = await ApiLeads.getInstance().getFieldsByIdArr(lead_ids);
        return result;
    }
    static async getContctByIdLead(lead_id: number): Promise<Contact> {
        const result = await ApiLeads.getInstance().getContctByIdLead(lead_id);
        return result;
    }
    static async getNoteByIdLead(lead_id: number): Promise<Contact> {
        const result = await ApiLeads.getInstance().getNoteByIdLead(lead_id);
        return result;
    }
}
