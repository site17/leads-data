import * as Schemes from "./scheme";
import { z } from "zod";

export type Leads = z.infer<typeof Schemes.SLeads>;
export type LeadsData = z.infer<typeof Schemes.SLeadsData>;
export type Status = z.infer<typeof Schemes.SStatus>;
export type Statuses = z.infer<typeof Schemes.SStatuses>;
export type SStatusesData = z.infer<typeof Schemes.SStatusesData>;
export type LeadField = z.infer<typeof Schemes.SLeadField>;
export type Contact = z.infer<typeof Schemes.SContact>;
