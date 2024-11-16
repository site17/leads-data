import { z } from "zod";

export const SLeads = z.object({
    id: z.number(),
    lead_id: z.number(),
    status_id: z.number().nullable(),
    pipeline_id: z.number().nullable(),
    name: z.string().nullable(),
    price: z.number().nullable(),
    raw_content: z.string().nullable(),
    created_at_amo: z.string().nullable(),
});

export const SLeadsData = z.object({
    data: z.array(SLeads),
    current_page:z.number(),
    total:z.number(),
});

export const SStatus = z.object({
    name: z.string(),
    color: z.string(),
});
export const SStatuses = z.object({
    status_id: z.number(),
    name: z.string(),
    color: z.string(),
});

export const SStatusesData = z.object({
    data: z.array(SStatuses),
    status: z.string(),
});

export const SLeadField = z.object({
    id: z.number(),
    lead_id: z.number(),
    field_id: z.number(),
    value: z.string(),
    created_at: z.string().nullable(),
    updated_at: z.string().nullable(),
});

export const SContact = z.object({
    id: z.number(),
    contact_id: z.number(),
    group_id: z.number(),
    account_id: z.number(),
    responsible_user_id: z.number(),
    name: z.string(),
    first_name: z.string().nullable(),
    last_name: z.string().nullable(),
    phone: z.string().nullable(),
    email: z.string().nullable(),
    custom_fields: z.string().nullable(),
    raw_content: z.string().nullable(),
    created_at: z.string().nullable(),
    updated_at: z.string().nullable(),
    deleted_at: z.string().nullable(),
});
