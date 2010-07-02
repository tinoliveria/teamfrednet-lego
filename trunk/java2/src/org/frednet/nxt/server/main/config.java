package org.frednet.nxt.server.main;

import java.io.File;
import org.w3c.dom.Document;
import org.w3c.dom.*;

import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.DocumentBuilder;
import org.xml.sax.SAXException;
import org.xml.sax.SAXParseException;

public class config {

	/**
	 * Load the config
	 */
	public config(){
		try {
			// just demo data
			NXTportRange[0] = 1;
			NXTportRange[1] = 50;
			//read config.xml
			DocumentBuilderFactory docBuilderFactory = DocumentBuilderFactory
					.newInstance();
			DocumentBuilder docBuilder = docBuilderFactory.newDocumentBuilder();
			Document doc = docBuilder.parse(new File("C:\\Users\\marc\\workspace\\frednet.nxt.java\\config.xml"));

			// normalize text representation
			doc.getDocumentElement().normalize();
			NodeList nxtconfig = doc.getElementsByTagName("item");
			for(int s=0; s<nxtconfig.getLength() ; s++){


                Node itemNode = nxtconfig.item(s);
                if(itemNode.getNodeType() == Node.ELEMENT_NODE){


                    Element ItemElement = (Element)itemNode;
                    String r = ItemElement.getElementsByTagName("name").item(0).getChildNodes().item(0).getNodeValue().trim(); 
                    if(r.equals("ComportRange".trim())){
                    	String result = ItemElement.getElementsByTagName("value").item(0).getChildNodes().item(0).getNodeValue().trim();
                    	String[] result_s = result.split("-");
                    	NXTportRange[0] = Short.parseShort(result_s[0]);
                    	NXTportRange[1] = Short.parseShort(result_s[1]);
                    }
                    if(r.equals("KeepAliveInterval".trim())){
                    	String result = ItemElement.getElementsByTagName("value").item(0).getChildNodes().item(0).getNodeValue().trim();
                    	KeepAliveInterval = Long.parseLong(result);
                    	
                    }
                    
                    


                }//end of if clause


            }//end of for loop with s var
			int totalItem = nxtconfig.getLength();
            System.out.println("Total no of options : " + totalItem);
			
		} catch (SAXParseException err) {
			System.out.println("** Parsing error" + ", line "
					+ err.getLineNumber() + ", uri " + err.getSystemId());
			System.out.println(" " + err.getMessage());

		} catch (SAXException e) {
			Exception x = e.getException();
			((x == null) ? e : x).printStackTrace();

		} catch (Throwable t) {
			t.printStackTrace();
		}
	}

	public static int NXTportRange[] = { 1, 50 };
	public static long KeepAliveInterval = 300;

}
