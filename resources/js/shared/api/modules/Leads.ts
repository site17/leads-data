// use App\Models\Contact;
// import { SStat, Stat, StatPost, StatPostTo } from "@/entities/stat";
import {
    Contact,
    LeadField,
    Leads,
    LeadsData,
    SContact,
    SLeadField,
    SLeads,
    SLeadsData,
    SStatus,
    SStatuses,
    SStatusesData,
    Status,
    Statuses,
} from "@/entities/leads";
import { BaseApi } from "./BaseApi";
import { z } from "zod";

export class ApiLeads extends BaseApi {
    private static instance: ApiLeads;
    // private readonly BASE_URL = "http://127.0.0.1:8000";
    // private readonly API_BASE_URL = "/api/stat";
    // private stat_id: number;
    // private integration_id: number;
    private constructor() {
        super();
    }
    public static getInstance(): ApiLeads {
        if (!ApiLeads.instance) {
            ApiLeads.instance = new ApiLeads();
        }
        return ApiLeads.instance;
    }
    public async getAllLeads() {
        const result: LeadsData = await this.get(
            {
                url: `/leads`,
            },
            SLeadsData
        );

        return result;
    }
    public async getLead(lead_id: number) {
        const result: Leads = await this.get(
            {
                url: `/leads/${lead_id}`,
            },
            SLeads
        );

        return result;
    }
    public async getLeadById(id: number) {
        const result: Leads = await this.get(
            {
                url: `/leads/${id}`,
            },
            SLeads
        );

        return result;
    }

    public async getStatusById(status_id: number) {
        const result: Status = await this.get(
            {
                url: `/status/${status_id}`,
            },
            SStatus
        );
        return result;
    }
    public async getStatuses() {
        const result: Array<Statuses> = await this.get(
            {
                url: `/status`,
            },
            z.array(SStatuses)
        );
        return result;
    }
    public async getFieldsByIdArr(lead_ids: Array<number>) {
        const result: Array<LeadField> = await this.post(
            {
                url: `/lead-fields`,
                data: { lead_ids },
            },
            z.array(SLeadField)
        );
        return result;
    }
    public async getContctByIdLead(lead_id: number) {
        const result: Contact = await this.get(
            {
                url: `/contacts/${lead_id}`,
            },
            SContact
        );
        return result;
    }

    public async getNoteByIdLead(lead_id: number) {
        const result: Contact = await this.get(
            {
                url: `/note/${lead_id}`,
            },
            SContact
        );
        return result;
    }
}
