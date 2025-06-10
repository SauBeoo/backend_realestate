import 'dotenv/config';
import { runServer } from '@benborla29/mcp-server-mysql';

(async () => {
    try {
        console.log('Starting MCP server...');
        console.log('Environment variables:', {
            host: process.env.MYSQL_HOST,
            port: process.env.MYSQL_PORT,
            user: process.env.MYSQL_USER,
            database: process.env.MYSQL_DB
        });
        await runServer();
    } catch (error) {
        console.error('Error starting MCP server:', error);
        process.exit(1);
    }
})();
