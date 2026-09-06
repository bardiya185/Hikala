export function formatDate(date, options = {}) {
    if (!date) return "-";
    
    const dateObj = typeof date === "string" ? new Date(date) : date;
    
    // Check if date is valid
    if (isNaN(dateObj.getTime())) return "-";
    
    const { 
      locale = "en-US", 
      options: dateOptions = {} 
    } = options;
    
    const defaultOptions = {
      year: "numeric",
      month: "short",
      day: "numeric",
      ...dateOptions,
    };
    
    return dateObj.toLocaleDateString(locale, defaultOptions);
  }
  
  /**
   * Format date with time
   * @param {string|Date|null} date - The date to format
   * @returns {string} Formatted date with time
   */
  export function formatDateTime(date) {
    if (!date) return "-";
    
    const dateObj = typeof date === "string" ? new Date(date) : date;
    
    if (isNaN(dateObj.getTime())) return "-";
    
    return dateObj.toLocaleDateString("en-US", {
      year: "numeric",
      month: "short",
      day: "numeric",
      hour: "2-digit",
      minute: "2-digit",
    });
  }
  
  /**
   * Format date for Persian/Farsi users
   * @param {string|Date|null} date - The date to format
   * @returns {string} Formatted date in Persian
   */
  export function formatPersianDate(date) {
    if (!date) return "-";
    
    const dateObj = typeof date === "string" ? new Date(date) : date;
    
    if (isNaN(dateObj.getTime())) return "-";
    
    return dateObj.toLocaleDateString("fa-IR", {
      year: "numeric",
      month: "long",
      day: "numeric",
    });
  }
  
  /**
   * Get relative time (e.g., "2 hours ago", "3 days ago")
   * @param {string|Date|null} date - The date to format
   * @returns {string} Relative time string
   */
  export function timeAgo(date) {
    if (!date) return "-";
    
    const dateObj = typeof date === "string" ? new Date(date) : date;
    
    if (isNaN(dateObj.getTime())) return "-";
    
    const now = new Date();
    const diffInSeconds = Math.floor((now - dateObj) / 1000);
    
    if (diffInSeconds < 60) {
      return `${diffInSeconds} seconds ago`;
    }
    
    const diffInMinutes = Math.floor(diffInSeconds / 60);
    if (diffInMinutes < 60) {
      return `${diffInMinutes} ${diffInMinutes === 1 ? "minute" : "minutes"} ago`;
    }
    
    const diffInHours = Math.floor(diffInMinutes / 60);
    if (diffInHours < 24) {
      return `${diffInHours} ${diffInHours === 1 ? "hour" : "hours"} ago`;
    }
    
    const diffInDays = Math.floor(diffInHours / 24);
    if (diffInDays < 30) {
      return `${diffInDays} ${diffInDays === 1 ? "day" : "days"} ago`;
    }
    
    const diffInMonths = Math.floor(diffInDays / 30);
    if (diffInMonths < 12) {
      return `${diffInMonths} ${diffInMonths === 1 ? "month" : "months"} ago`;
    }
    
    const diffInYears = Math.floor(diffInMonths / 12);
    return `${diffInYears} ${diffInYears === 1 ? "year" : "years"} ago`;
  }
  
  /**
   * Check if a date is today
   * @param {string|Date|null} date - The date to check
   * @returns {boolean} True if date is today
   */
  export function isToday(date) {
    if (!date) return false;
    
    const dateObj = typeof date === "string" ? new Date(date) : date;
    
    if (isNaN(dateObj.getTime())) return false;
    
    const today = new Date();
    return (
      dateObj.getFullYear() === today.getFullYear() &&
      dateObj.getMonth() === today.getMonth() &&
      dateObj.getDate() === today.getDate()
    );
  }
  
  /**
   * Check if a date is in the future
   * @param {string|Date|null} date - The date to check
   * @returns {boolean} True if date is in the future
   */
  export function isFutureDate(date) {
    if (!date) return false;
    
    const dateObj = typeof date === "string" ? new Date(date) : date;
    
    if (isNaN(dateObj.getTime())) return false;
    
    return dateObj > new Date();
  }
  
  /**
   * Check if a date is in the past
   * @param {string|Date|null} date - The date to check
   * @returns {boolean} True if date is in the past
   */
  export function isPastDate(date) {
    if (!date) return false;
    
    const dateObj = typeof date === "string" ? new Date(date) : date;
    
    if (isNaN(dateObj.getTime())) return false;
    
    return dateObj < new Date();
  }
  
  /**
   * Get the difference between two dates in days
   * @param {string|Date} date1 - First date
   * @param {string|Date} date2 - Second date (default: now)
   * @returns {number} Difference in days
   */
  export function daysBetween(date1, date2 = new Date()) {
    const d1 = typeof date1 === "string" ? new Date(date1) : date1;
    const d2 = typeof date2 === "string" ? new Date(date2) : date2;
    
    if (isNaN(d1.getTime()) || isNaN(d2.getTime())) return 0;
    
    const diffTime = Math.abs(d2 - d1);
    return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
  }
  
  /**
   * Format date for input fields (YYYY-MM-DD)
   * @param {string|Date|null} date - The date to format
   * @returns {string} Formatted date for input
   */
  export function formatDateForInput(date) {
    if (!date) return "";
    
    const dateObj = typeof date === "string" ? new Date(date) : date;
    
    if (isNaN(dateObj.getTime())) return "";
    
    const year = dateObj.getFullYear();
    const month = String(dateObj.getMonth() + 1).padStart(2, "0");
    const day = String(dateObj.getDate()).padStart(2, "0");
    
    return `${year}-${month}-${day}`;
  }
  
  // Default export
  export default {
    formatDate,
    formatDateTime,
    formatPersianDate,
    timeAgo,
    isToday,
    isFutureDate,
    isPastDate,
    daysBetween,
    formatDateForInput,
  };